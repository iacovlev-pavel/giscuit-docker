# PostGIS
### Install PostGIS
https://postgis.net/install/

Ubuntu 18.04 example:
`sudo apt-get install postgresql-10-postgis-2.4 postgresql-10`

### Create PostgreSQL user and database
```
sudo su postgres

createuser -P giscuit
createdb giscuit -O giscuit

psql -d giscuit
CREATE EXTENSION postgis;
CREATE EXTENSION pg_trgm;
\q
```

Add
```
# Allow connections from localhost
host    all     all     127.0.0.1/32    md5
host    all     all     ::1/128 md5
```
to the end of `/etc/postgresql/10/main/pg_hba.conf`
and restart PostgreSQL

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
Database username: giscuit
Database password: The one specified during `createdb` operation.

# Debug
`docker exec -i -t giscuit /bin/bash`
