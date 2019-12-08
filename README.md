### Install docker
https://docs.docker.com/install/

### Pull docker image
`git clone https://github.com/iacovlev-pavel/giscuit-docker.git`

### Build
`sudo docker build github.com/iacovlev-pavel/giscuit-docker -t giscuit-docker`

### Run
`sudo docker run -p 80:80 --name giscuit -itd giscuit-docker`

### Connect
`docker exec -i -t giscuit /bin/bash`
