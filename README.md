# WP Headless Simple Contact Form
As the name suggests this is a simple plugin, which you can expand on to your liking. However if all you need is a simple contact form to send from your JAMstack site, it should be good to go.

## Setting Up
You'll only need to set where you'd like to receive emails and a subject line(if the website visitors will be sending the subject you'll need to add a field for that as shown below).

```php
<?php
$send_to_email = 'YOUR_EMAIL_GOES_HERE';
$subject = 'EMAIL_SUBJECT_GOES_HERE';
```

## Adding Fields
Currently it only accepts 3 fields, but you can easily add your own. Head over to *wp-headless-contact-form.php* and add your field as follow

### 1. First step
Store value of new field and **make sure to sanitize all request fields**
```php
<?php
$new_field = sanitize_text_field( trim( $request['new_field']) );
```


### 2. Check that the value is not empty and is valid
```php
<?php
if( empty( $new_field ) ) {
    $errors['new_field'] = 'This field is required';
}
```

## Sending a POST request with Axios.

```js
const form = document.getElementById('my-form');
const formData = new FormData(form);

axios.post('https://your-domain.com/wp-json/send-contact-form/v1/contact', formData)
.then(({data}) => {
    // data.success will be true if successful
    // else you'll receive the error messages to display under each input field
}
```
