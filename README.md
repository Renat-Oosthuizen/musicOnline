# musicOnline
This is a website that I coded for a college software development project. It is a prototype for a web store selling vinyl music disks.

Note: databaseConnect.php has been modified to remove sensitive information.
You can find the website running live on: www.renatoosthuizen.com

The website is connected to a MySQL database. Appropriate measures are used to make the website safe from SQL injection attacks.
New users are able to register and their password is salted and hashed before being stored.
The website uses redirects to make sure that only logged users can access internal pages.
The website has Regular Users and Admin Users.

Regular users can search for and view the vinyls being sold. The search terms include title and artist. Vinyl data includes an image, title, publisher, artist, release date, running time, price and description.
They can also create their own vinyls to sell as well as edit and delete them. Additionally, they can edit and delete their own account. Account information includes email, phone number and password.

Admin Users can search for vinyls and Regular Users. They can edit and delete user account and vinyl entries.


Login Details:
  Regular User: dovakin@gmail.com
  Password: musicOnlinePassword
  
  Admin User: adminguy@gmail.com
  Password: password123
