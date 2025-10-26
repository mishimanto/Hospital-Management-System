<?php
function getTestBookingEmailTemplate($order_number, $test_names, $total_amount, $test_date) {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #00b894; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background-color: #f9f9f9; }
            .footer { padding: 10px; text-align: center; font-size: 12px; color: #777; }
            .button { background-color: #00b894; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>Test Booking Confirmation</h2>
            </div>
            
            <div class="content">
                <p>Dear Patient,</p>
                
                <p>Your diagnostic test booking has been confirmed with the following details:</p>
                
                <table>
                    <tr>
                        <td><strong>Order Number:</strong></td>
                        <td>' . $order_number . '</td>
                    </tr>
                    <tr>
                        <td><strong>Tests Booked:</strong></td>
                        <td>' . $test_names . '</td>
                    </tr>
                    <tr>
                        <td><strong>Total Amount:</strong></td>
                        <td>৳' . number_format($total_amount, 2) . '</td>
                    </tr>
                    <tr>
                        <td><strong>Scheduled Date:</strong></td>
                        <td>' . date('F j, Y', strtotime($test_date)) . '</td>
                    </tr>
                </table>
                
                <p style="margin-top: 20px;">
                    Please arrive at our diagnostic center at least 15 minutes before your scheduled time.
                    Don\'t forget to bring any required preparation items mentioned in the test details.
                </p>
                
                <p style="text-align: center; margin-top: 30px;">
                    <a href="#" class="button">View Your Booking</a>
                </p>
            </div>
            
            <div class="footer">
                <p>Thank you for choosing MEDIZEN Diagnostic Center.</p>
                <p>If you have any questions, please contact us at support@medizen.com</p>
            </div>
        </div>
    </body>
    </html>
    ';
}
?>