# Post-its App

Post-its App allows you to make and subscribe to a group and share ideas with Post-its!

## To Start

1. Execute `composer install`
2. Copy the `.env.example` and rename to `.env`
3. Set this variables on `.env` file:
   - DB_*
   - MAIL_*
   - JWT_SECRET
   - GOOGLE_DRIVE_*
4. To generate the `JWT_SECRET` use `php artisan jwt:secret`
5. To generate the `MAIL_*`, you should use an email provider. For tests, you can use gmail. Check the instructions here [How to make an application password](https://programacionymas.com/blog/como-enviar-mails-correos-desde-laravel#:~:text=Para%20dar%20la%20orden%20a,su%20orden%20ha%20sido%20enviada.)
6. To generate the `GOOGLE_DRIVE_*`, you need to create a project on google console. Check the instructions here: [Create your Google Drive API Keys](https://github.com/ivanvermeyen/laravel-google-drive-demo#create-your-google-drive-api-keys)
7. Run `php artisan serve`

**Note:** All routes are protected. Only `/api/login` and `api/register` doesn't have protection.
**Note 2:** The protected routes can be accesed using a `jwt` token on the headers (with type `Bearer`). You can get a token when login.

