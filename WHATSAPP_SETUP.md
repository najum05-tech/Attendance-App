# WhatsApp Notification Setup Guide

This guide will help you configure WhatsApp notifications for the Attendance Management System.

## Supported Providers

The system supports three WhatsApp providers:

1. **Twilio** (Recommended for most users)
2. **WhatsApp Business API** (Meta/Facebook)
3. **Generic HTTP API** (Custom providers)

---

## Option 1: Twilio Setup (Recommended)

### Step 1: Create Twilio Account
1. Go to [https://www.twilio.com](https://www.twilio.com)
2. Sign up for a free account
3. Verify your email and phone number

### Step 2: Get Twilio Credentials
1. Go to [Twilio Console](https://www.twilio.com/console)
2. Find your **Account SID** and **Auth Token**
3. Copy these values

### Step 3: Set Up WhatsApp Sandbox
1. Go to [Twilio WhatsApp Sandbox](https://www.twilio.com/console/sms/whatsapp/sandbox)
2. Follow the instructions to join the sandbox
3. Note your WhatsApp number (format: `whatsapp:+14155238886`)

### Step 4: Configure .env File
Add these lines to your `.env` file:

```env
WHATSAPP_PROVIDER=twilio
TWILIO_ACCOUNT_SID=your_account_sid_here
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
```

Optional: set the default country code used when phone numbers are provided without a country code (e.g., `0xxxxxxxxxx`):

```env
WHATSAPP_DEFAULT_COUNTRY_CODE=92
```

### Step 5: Add Phone Numbers to Users
Users need phone numbers in their profile. You can:
- Add phone field to user registration form
- Update existing users via admin panel
- Run migration: `php artisan migrate`

---

## Option 2: WhatsApp Business API Setup

### Step 1: Create Facebook App
1. Go to [Facebook Developers](https://developers.facebook.com)
2. Create a new app
3. Add WhatsApp product to your app

### Step 2: Get Access Token
1. In your app dashboard, go to WhatsApp > API Setup
2. Get your **Temporary Access Token** (or create a permanent one)
3. Note your **Phone Number ID** and **Business Account ID**

### Step 3: Configure .env File
Add these lines to your `.env` file:

```env
WHATSAPP_PROVIDER=whatsapp_business
WHATSAPP_BUSINESS_TOKEN=your_access_token_here
WHATSAPP_BUSINESS_PHONE_ID=your_phone_number_id_here
WHATSAPP_BUSINESS_ACCOUNT_ID=your_business_account_id_here
```

---

## Option 3: Generic HTTP API Setup

If you're using a custom WhatsApp API provider:

### Configure .env File
```env
WHATSAPP_PROVIDER=generic
WHATSAPP_API_URL=https://your-api-provider.com/send
WHATSAPP_API_KEY=your_api_key_here
```

Your API should accept POST requests with:
- `to`: Phone number
- `message`: Message text
- Authorization header with API key

---

## Testing

1. Make sure a user has a phone number in their profile
2. Mark attendance or perform an action that triggers notification
3. Check Laravel logs: `storage/logs/laravel.log`
4. Look for "WhatsApp Notification" entries

---

## Troubleshooting

### Notifications Not Sending?

1. **Check Logs**: Look in `storage/logs/laravel.log` for error messages
2. **Verify Credentials**: Double-check all API keys and tokens
3. **Phone Format**: Ensure phone numbers include country code (e.g., +1234567890)
4. **Provider Status**: Check your provider's status page
5. **User Phone**: Ensure users have phone numbers in their profiles

### Common Errors

- **"Missing credentials"**: Check your `.env` file has all required values
- **"Invalid phone number"**: Phone must include country code
- **"API rate limit"**: You've exceeded your provider's rate limit

---

## Production Considerations

1. **Rate Limits**: Be aware of your provider's rate limits
2. **Error Handling**: The system logs errors but continues operation
3. **Queue Jobs**: Consider using Laravel queues for better performance
4. **Testing**: Always test in sandbox/test mode before production

---

## Support

For provider-specific issues:
- **Twilio**: [Twilio Support](https://support.twilio.com)
- **WhatsApp Business**: [Meta Business Support](https://business.facebook.com/help)

