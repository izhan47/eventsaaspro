<?php
namespace Eventsaaspro\Commands;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ControllersCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eventsaaspro-pro:controllers';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all the controllers from EventSaaSPro.';
    /**
     * The Filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * Filename of stub-file.
     *
     * @var string
     */
    protected $stub = 'controller.stub';
    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        parent::__construct();
    }

    public function fire()
    {
        return $this->handle();
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->create_controller();
        $this->create_controller('Auth');
        $this->create_controller('Voyager');

        $this->info('Published EventSaaSPro controllers!');
    }

    /**
     *  create controller
     */
    protected function create_controller($controller_folder = null)
    {
        $stub = $this->getStub();

        $file      = [];
        $namespace = null;

        if(empty($controller_folder))
        {
            $files = $this->filesystem->files(base_path('vendor/classiebit/eventsaaspro-pro/src/Http/Controllers'));
            $namespace = config('eventsaaspro.controllers.namespace', 'Eventsaaspro\\Http\\Controllers');
        }
        else
        {
            $files = $this->filesystem->files(base_path('vendor/classiebit/eventsaaspro-pro/src/Http/Controllers/'.$controller_folder));
            $namespace = config('eventsaaspro.controllers.namespace',                                              'Eventsaaspro\\Http\\Controllers\\'.$controller_folder);

            $namespace = $namespace.'\\'.$controller_folder;
        }

        $appNamespace = app()->getNamespace();

        if (!Str::startsWith($namespace, $appNamespace)) {
            return $this->error('The controllers namespace must start with your application namespace: '.$appNamespace);
        }
        $location = str_replace('\\', DIRECTORY_SEPARATOR, substr($namespace, strlen($appNamespace)));


        if (!$this->filesystem->isDirectory(app_path($location))) {
            $this->filesystem->makeDirectory(app_path($location), 0755, true);
        }
        foreach ($files as $file) {
            $parts = explode(DIRECTORY_SEPARATOR, $file);
            $filename = end($parts);
            if ($filename == 'Controller.php') {
                continue;
            }
            $path = app_path($location.DIRECTORY_SEPARATOR.$filename);
            if (!$this->filesystem->exists($path) or $this->option('force')) {
                $class = substr($filename, 0, strpos($filename, '.'));
                $content = $this->generateContent($stub, $class, $controller_folder);
                $this->filesystem->put($path, $content);
            }
        }
    }

    /**
     * Get stub content.
     *
     * @return string
     */
    public function getStub()
    {
        return $this->filesystem->get(base_path('/vendor/classiebit/eventsaaspro-pro/stubs/'.$this->stub));
    }
    /**
     * Generate real content from stub.
     *
     * @param $stub
     * @param $class
     *
     * @return mixed
     */
    protected function generateContent($stub, $class, $controller_folder)
    {
        if(empty($controller_folder))
        {
            $namespace = config('eventsaaspro.controllers.namespace', 'Eventsaaspro\\Http\\Controllers');
        }
        else
        {
            $namespace = config('eventsaaspro.controllers.namespace', 'Eventsaaspro\\Http\\Controllers');
            $namespace = $namespace.'\\'.$controller_folder;
        }

        $content = str_replace(
            'DummyNamespace',
            $namespace,
            $stub
        );

        if(empty($controller_folder))
        {
            $content = str_replace(
                'FullBaseDummyClass',
                'Eventsaaspro\\Http\\Controllers\\'.$class,
                $content
            );
        }
        else
        {
            $content = str_replace(
                'FullBaseDummyClass',
                'Eventsaaspro\\Http\\Controllers\\'.$controller_folder.'\\'.$class,
                $content
            );
        }

        $content = str_replace(
            'BaseDummyClass',
            'Base'.$class,
            $content
        );

        $content = str_replace(
            'DummyClass',
            $class,
            $content
        );

        return $content;
    }
    /**
     * Get command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing controller files'],
        ];
    }


}
