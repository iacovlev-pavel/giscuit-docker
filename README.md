### Install docker
https://docs.docker.com/install/

### Pull docker image
`git clone git@github.com:iacovlev-pavel/giscuit-docker.git`

### Build
`docker build github.com/iacovlev-pavel/giscuit-docker -t giscuit-docker`

### Run
`docker run -p 80:80 --name giscuit -itd giscuit-docker`

### Connect
`docker exec -i -t giscuit /bin/bash`
