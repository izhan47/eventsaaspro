<?php

namespace Eventsaaspro\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Eventsaaspro\Models\Booking;
use Eventsaaspro\Models\Commission;
use Eventsaaspro\Models\Event;
use Eventsaaspro\Models\Ticket;
use Eventsaaspro\Models\Transaction;
use Eventsaaspro\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // language change
        $this->middleware('common');

        // download only after login
        $this->middleware('auth');

        $this->event = new Event;
        $this->ticket = new Ticket;
        $this->booking = new Booking;
        $this->transaction = new Transaction;
        $this->commission = new Commission;
    }

    /**
     * Show my booking
     *
     * @return array
     */
    public function index($id = null, $order_number = null)
    {
        if (!empty(setting('booking.hide_ticket_download')) && (Auth::user()->hasRole('organiser') || Auth::user()->hasRole('customer'))) {
            abort('404');
        }

        $id = (int) $id;
        $order_number = trim($order_number);

        // get the booking
        $booking = $this->booking->get_event_bookings(['id' => $id, 'order_number' => $order_number]);
        if (empty($booking)) {
            abort('404');
        }

        $booking = $booking[0];

        // customer can see only their bookings
        if (Auth::user()->hasRole('customer')) {
            if ($booking['customer_id'] != Auth::id()) {
                abort('404');
            }
        }

        // organiser can see only their events bookings
        if (Auth::user()->hasRole('organiser')) {
            if ($booking['organiser_id'] != Auth::id()) {
                abort('404');
            }
        }

        // generate QrCode
        $qrcode_data = [
            'id' => $booking['id'],
            'order_number' => $booking['order_number'],
        ];
        $this->createQrcode($booking, $qrcode_data);

        // get event data for ticket pdf
        $event = $this->event->get_event(null, $booking['event_id']);
        $currency = setting('regional.currency_default');

        // generate PDF
        // test PDF
        // $img_path = '';
        // return EventSaaSPro::view('eventsaaspro::tickets.pdf', compact('booking', 'event', 'currency', 'img_path'));
        // use http url only
        $img_path = str_replace('https://', 'http://', url(''));
        $pdf_html = (string) \View::make('eventsaaspro::tickets.pdf', compact('booking', 'event', 'currency', 'img_path'));
        $pdf_name = $booking['id'] . '-' . $booking['order_number'];
        $this->generatePdf($pdf_html, $pdf_name, $booking);

        // download PDF
        $path = 'ticketpdfs/' . $booking['customer_id'] . '/';
        $pdf_file = $path . $booking['id'] . '-' . $booking['order_number'] . '.pdf';

// Check if the file exists in S3
        if (!Storage::disk('s3')->exists($pdf_file)) {
            abort(404);
        }

// Get the file contents from S3
        $fileContents = Storage::disk('s3')->get($pdf_file);

// Return a downloadable response
        return response($fileContents, 200)->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $pdf_file . '"');

    }

    protected function createQrcode($data = [], $qrcode_data = [])
    {
        $path = 'qrcodes/' . $data['customer_id'] . '/';
        $s3Path = $path . $data['id'] . '-' . $data['order_number'] . '.png';

        // Upload to S3 only if the file doesn't already exist
        if (!Storage::disk('s3')->exists($s3Path)) {
            // Generate QrCode
            $qrcode_image = \QrCode::format('png')->size(512)->generate(json_encode($qrcode_data));

            // Upload to S3
            Storage::disk('s3')->put($s3Path, (string) $qrcode_image, 'public');
        }

        return true;
    }

    /**
     *  generate pdf
     */
    protected function generatePdf($html = null, $pdf_name = null, $data = [])
    {
        $path = 'ticketpdfs/' . $data['customer_id'] . '/';

        // Check if the directory exists in S3
        if (!Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->makeDirectory($path);
        }

        $pdf_file = $path . $data['id'] . '-' . $data['order_number'] . '.pdf';

        // Check if the file exists in S3
        if (!Storage::disk('s3')->exists($pdf_file)) {
            // Start PDF generation
            $html = preg_replace('/>\s+</', '><', $html);
            if (empty($html)) {
                return false;
            }

            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
            $options = [
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isJavascriptEnabled' => false,
                'debugKeepTemp' => true,
                'isHtml5ParserEnabled' => true,
                'enable_html5_parser' => true,
            ];
            $pdfOutput = \PDF::setOptions($options)
                ->loadHTML($html)
                ->setWarnings(false)
                ->setPaper('a4', 'portrait')
                ->output();

            $localPdfDirectory = storage_path('app/public/') . $path;
            if (!file_exists($localPdfDirectory)) {
                mkdir($localPdfDirectory, 0755, true);
            }
            $localPdfPath = storage_path('app/public/') . $pdf_file;

            file_put_contents($localPdfPath, $pdfOutput);

            Storage::disk('s3')->put($pdf_file, file_get_contents($localPdfPath), 'public');
            unlink($localPdfPath);
        }

        return true;
    }

}
