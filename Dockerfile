#
# Ubuntu Dockerfile
#
# https://github.com/dockerfile/ubuntu
#

# Pull base image.
FROM ubuntu:14.04

# Install.
RUN \
  sed -i 's/# \(.*multiverse$\)/\1/g' /etc/apt/sources.list && \
  DEBIAN_FRONTEND=noninteractive apt-get update && \
  DEBIAN_FRONTEND=noninteractive apt-get -y upgrade && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y build-essential && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y software-properties-common && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y byobu curl git htop man wget php5-cli nano && \
  rm -rf /var/lib/apt/lists/*

# Install Giscuit.
ADD root/install.php /root/install.php
RUN php /root/install.php

# Add files.
ADD root/.bashrc /root/.bashrc
ADD root/.gitconfig /root/.gitconfig
ADD root/.scripts /root/.scripts

# Set environment variables.
ENV HOME /root

# Define working directory.
WORKDIR /root

# Define default command.
CMD ["bash"]
