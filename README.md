<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Documentation
Link : https://drive.google.com/file/d/1Dl2jNmg-3wDlITuiWxL_5yA92BAISlgL/view?usp=sharing

## Project Background
Link : https://drive.google.com/file/d/18W8NmHIoEHIFC4E6x_p7lMEAeNR7HXya/view?usp=sharing

## How To Clone This Project
<ol>
  <li>
    <strong>Install PHP dependencies using Composer:</strong><br>
    <code>composer install</code>
  </li>

  <li>
    <strong>Copy the example environment file:</strong><br>
    <code>cp .env.example .env</code><br>
    <em>On Windows:</em> <code>copy .env.example .env</code>
  </li>

  <li>
    <strong>Generate the application key:</strong><br>
    <code>php artisan key:generate</code>
  </li>

  <li>
    <strong>Configure your database in the <code>.env</code> file:</strong><br>
    Update these lines with your local database credentials:
    <pre>
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
    </pre>
  </li>

  <li>
    <strong>Run database migrations:</strong><br>
    <code>php artisan migrate</code>
  </li>

  <li>
    <strong>(Optional) Seed the database with test or default data:</strong><br>
    <code>php artisan db:seed</code><br>
    Or run both migrate and seed together:<br>
    <code>php artisan migrate:fresh --seed</code> <br><br>

   
  </li>
   <em>⚠️ If the seeder fails (e.g. admin user is not created), you can manually register an admin using the registration form.</em><br>
    Please refer to the documentation for the registration page: <br>
    <a class="cta-btn"
       href="https://drive.google.com/file/d/1Dl2jNmg-3wDlITuiWxL_5yA92BAISlgL/view?usp=sharing"
       target="_blank"
       rel="noopener noreferrer">
       Documentation
    </a>

  <li>
    <strong>Start the Laravel development server:</strong><br>
    <code>php artisan serve</code><br>
    Then open <a href="http://localhost:8000" target="_blank" rel="noopener noreferrer">http://localhost:8000</a> in your browser.
  </li>

  <li>
    <strong>Install frontend:</strong><br>
    <code>npm install</code><br>
    <code>npm run dev</code>
  </li>
</ol>

<p>✅ You’re all set! Happy coding!</p>


