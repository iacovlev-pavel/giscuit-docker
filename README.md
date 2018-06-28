### Base Docker Image
* [ubuntu:14.04](https://registry.hub.docker.com/u/library/ubuntu/)

### Installation

### Usage
    docker build github.com/iacovlev-pavel/giscuit-docker -t giscuit-docker
    docker run --expose 80 --name giscuit -it giscuit-docker