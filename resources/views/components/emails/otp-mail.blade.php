 @props(['otp', 'name'])
{{--
 name : {{ $name }}
 OTP : {{ $otp }} --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OTP Verification - {{ config('app.name') }}</title>
    <!-- General styling for email clients - best practices dictate inline styles -->
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Resets */
        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: Arial, Helvetica, sans-serif;
            /* Fallback fonts */
        }

        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .main-container {
            max-width: 600px;
            margin: 0 auto;
            border-spacing: 0;
            border-collapse: collapse;
        }

        .content-cell {
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            /* Soft shadow */
        }

        .otp-box {
            background-color: #f0f8ff;
            /* Light blue background */
            border: 1px solid #cceeff;
            /* Light blue border */
            border-radius: 8px;
            padding: 20px 0;
            margin: 25px 0;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            /* Primary blue for OTP */
            letter-spacing: 3px;
            /* Space out the numbers */
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff !important;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-text {
            font-size: 12px;
            color: #888888;
            text-align: center;
            padding-top: 25px;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #666666;
        }

        .link {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Arial', sans-serif; line-height: 1.6; color: #333333;">

    <!-- Outer Table for Centering and Background -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0"
        style="background-color: #f4f7f6; min-width: 100%;">
        <tr>
            <td align="center" valign="top" style="padding: 40px 0;">
                <!-- Main Container Table -->
                <table class="main-container" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <!-- Header/Logo Area (Optional) -->
                    <tr>
                        <td class="content-cell"
                            style="padding: 20px 30px; background-color: #ffffff; border-bottom: 1px solid #eeeeee; border-radius: 8px 8px 0px 0px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="font-size: 28px; font-weight: bold; color: #333333;">
                                        <!-- Placeholder for Logo if you have one -->
                                        <!-- <img src="https://placehold.co/150x40/007bff/ffffff?text=LOGO" alt="Company Logo" width="150" height="40" style="display: block; border: 0;"> -->
                                        {{ config('app.name') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="content-cell" style="border-radius: 0px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-size: 16px; padding-bottom: 15px;">
                                        Hello <strong style="color: #007bff;">{{ $name }}</strong>,
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 16px; padding-bottom: 20px;">
                                        Thank you for registering with us! To secure your account and complete your
                                        registration, please use the following One-Time Password (OTP):
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <div class="otp-box">
                                            {{ $otp }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 16px; padding-top: 15px; padding-bottom: 10px;">
                                        This OTP is valid for <strong style="color: #d9534f;">2 minutes</strong>. Please
                                        enter it on our verification page to proceed.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 16px; padding-bottom: 20px;">
                                        <strong style="color: #d9534f;">Important:</strong> For your security, do not
                                        share this OTP with anyone.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 16px; padding-top: 10px;">
                                        If you did not request this verification, please ignore this email.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 16px; padding-top: 25px;">
                                        Thanks,<br>
                                        The Team at {{ config('app.name') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer Area -->
                    <tr>
                        <td
                            style="padding: 20px 30px; background-color: #ffffff; border-top: 1px solid #eeeeee; border-radius: 0px 0px 8px 8px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="footer-text">
                                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
