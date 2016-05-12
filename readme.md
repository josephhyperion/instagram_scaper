## Synopsis

This script will run on a PHP server to search Instagram API, parse JSON, and store the actual photos in a download folder. Each user will be 

## Installation

1. Downlad files

2. Install a local PHP server. MAMP is recommended: https://www.mamp.info/en/ 
If on Windows, WAMP http://www.wampserver.com/en/

3. If you have an instagram token, update config.php with your token. If you don't have a token, proceed to step 4.

4. Point local server to run on the downloaded directory and start your server

5. If you have not completed step 3, you will be asked to input a token. If you don't have a token, you can get one at http://instagram.pixelunion.net/

## Usage
Once set up, you can scrape users in two ways:

A. Enter usernames line-by-line in the text area

B. Upload a CSV file of users. (Heading for the username row must be "instagram" in order for this to work.)

Once scraping is complete, you can zip up files with the button provided and download a CSV of all images for later usage. Each image will be keyed with the corresponding username.

## Warning
If you close your browser while scraping, downloads will continue. Make sure to stop your PHP server!



## Requirements

PHP with configuration as outlined in php.ini file. If running on a hosted server, you may need to turn off security settings to allow the files to read from URL and save to server directories either via core config or HTACCES
 