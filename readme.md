# Event Management System

## Introduction

This project is an Event Management System designed to facilitate the creation and management of events. The system includes portals for Admin, Organizer, Comedian, and Customer. The core functionalities include event creation, ticket purchasing, e-ticket generation, and payment processing through Stripe and PayPal.

## Technology Stack

- **Backend:** Laravel
- **Frontend:** Vue.js
- **Database:** MySQL

## Key Features

### Admin Portal

- Manage all users (Organizers, Comedians, and Customers)
- Create and manage events
- Monitor ticket sales and payments
- Generate reports

### Organizer Portal

- Create and manage events
- Attach comedians to events
- Scan QR codes for ticket validation

### Customer Portal

- Browse and view events
- Purchase tickets using Stripe or PayPal
- Receive e-tickets with downloadable PDF and QR code

## Usage

1. **Access the application:**
    - Open your browser and go to `http://localhost:8000`

2. **Admin Portal:**
    - Manage users, events, and monitor activities.

3. **Organizer Portal:**
    - Create and manage events, attach comedians, and validate tickets.

4. **Customer Portal:**
    - Browse events, purchase tickets, and download e-tickets.

## Payment Integration

The Event Management System supports payment processing through Stripe and PayPal, ensuring secure and efficient transactions for ticket purchases.

### Stripe Integration

1. **Usage:**
    - Customers can select Stripe as a payment method during the ticket purchase process.
    - Upon successful payment, an e-ticket is generated and can be downloaded as a PDF or viewed with a QR code for event entry.

### PayPal Integration

1. **Usage:**
    - Customers can select PayPal as a payment method during the ticket purchase process.
    - Upon successful payment, an e-ticket is generated and can be downloaded as a PDF or viewed with a QR code for event entry.

