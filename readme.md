<center>Checking Subscriber Status</center>


## My Mportals Domain

- **http://mymportals.com/**


## Developer

If you need help related smartkid, please send an e-mail to Aung Thura Win via [mgaungthurawin@gmail.com](mgaungthurawin@gmail.com). Before you contect me please read the below project document.

## My Mportals Web Portal

Smart Kid has below modules

- HE API Integration
- HE Callback 
- Msisdn Description
- MA Integration


## Project Requirement 

- Laravel Version (5.7)
- PHP Version (7.1^) required
- Composer

## Main Code Files

- web.php (Handle Route)
- App\Http\Middleware\SignatureMiddleware.php is main handler to show content or not after subscription
- WebController.php (Handle all project in that Controller)
- config/education.php (Store all education contents)
- config/health.php (Store all baby health contents)
- config/story.php (Store all kid stories contents)
- main view/blade path is resources/views/web/

## Media and Resources

- public/smart-kid-articles has images and video of health, story, education
- public/games hase html5 games of Funny Game and Brain Tester
- public/web/css and public/web/js is main UI/UX resource


## Database and Tables

- kids and favourites only two tables required in this project
- All migration files are include in database/migrations path

## Hosting/Server

Server is Digital Ocean Droplet. Server credentials will provide via mail.
