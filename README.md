[www.xlmeapp.com](https://www.xlmeapp.com/)


# xlme

A project that allows you to collab, discover and manage your datasets

###
 - Built with Laravel
 - Docker
 - MYSQL
 - MQTT
 - AWS: Hosting


### Running Locally
To run this project locally. Amend the user variable inside the Dockerfile to match your system user. Then run:
```
docker compose up -d --build
docker exec -it app bash
npm run dev
```
To stop the containers run:
```
docker compose stop
```



Upload a spreadsheet
 - Config list that allows to clean rows
View a spreadsheet
 - Sort the columns
 - Remove columns where
 - Add a new column
 - Add a new row
 - Rename columns
 - Rename row values

Export a spreadsheet with functions, colours

Keep a history of updates to a spreadsheet