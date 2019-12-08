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

### Debug
`docker exec -i -t giscuit /bin/bash`
