### Base Docker Image
* [ubuntu:14.04](https://registry.hub.docker.com/u/library/ubuntu/)

### Installation
* https://docs.docker.com/install/

### Build
    docker build github.com/iacovlev-pavel/giscuit-docker -t giscuit-docker

### Run
    docker run -p 80:80 --name giscuit -itd giscuit-docker

### Connect
    docker exec -i -t giscuit /bin/bash