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

![Login](https://user-images.githubusercontent.com/79414856/114721820-33073200-9d31-11eb-89e3-663f231b9c95.GIF)
![Register](https://user-images.githubusercontent.com/79414856/114721852-38fd1300-9d31-11eb-879a-c72412609cdb.GIF)
![Admin Monitoring](https://user-images.githubusercontent.com/79414856/114721878-3d293080-9d31-11eb-847a-b6bd793e83cc.GIF)
![Edit Account](https://user-images.githubusercontent.com/79414856/114721893-41554e00-9d31-11eb-8261-5ff765023add.GIF)
![Edit Item](https://user-images.githubusercontent.com/79414856/114721907-43b7a800-9d31-11eb-9aa4-73ad509e5956.GIF)
![Search Records](https://user-images.githubusercontent.com/79414856/114721957-4d411000-9d31-11eb-850b-1eb0491ea64f.GIF)
![My Sales](https://user-images.githubusercontent.com/79414856/114721939-49ad8900-9d31-11eb-9606-e032913df373.GIF)
