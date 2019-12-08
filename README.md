# PostGIS
### Install PostGIS
https://postgis.net/install/

Ubuntu 18.04 example:
`sudo apt-get install postgresql-10-postgis-2.4 postgresql-10`

### Create PostgreSQL user and database
```
sudo su postgres

createuser giscuit
createdb giscuit -O giscuit

psql -d giscuit
CREATE EXTENSION postgis;
CREATE EXTENSION pg_trgm;
```

# Giscuit
### Install docker
https://docs.docker.com/install/

### Pull docker image
`git clone https://github.com/iacovlev-pavel/giscuit-docker.git`

### Build image
`sudo docker build github.com/iacovlev-pavel/giscuit-docker -t giscuit-docker`

### Run image
`sudo docker run -p 80:80 --name giscuit -itd giscuit-docker`

### Start Giscuit web install
Open `http://IP_ADDRESS/install.php` in browser.

# Debug
`docker exec -i -t giscuit /bin/bash`
