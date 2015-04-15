#!/bin/bash
#
zypper --non-interactive in php5-bcmath
zypper --non-interactive in php5-devel
zypper --non-interactive in php5-ftp
zypper --non-interactive in php5-mbstring
zypper --non-interactive in php5-mcrypt
zypper --non-interactive in php5-pcntl
zypper --non-interactive in php5-posix
zypper --non-interactive in php5-shmop
zypper --non-interactive in php5-snmp
zypper --non-interactive in php5-soap
zypper --non-interactive in php5-sockets
zypper --non-interactive in php5-sysvmsg
zypper --non-interactive in php5-sysvsem
zypper --non-interactive in php5-sysvshm
zypper --non-interactive in php5-tidy
zypper --non-interactive in php5-zip


#PARA CORRER svn EN LOS SERVIDORES

zypper --non-interactive in subversion-devel
zypper --non-interactive in php5-pear
zypper --non-interactive in php5-curl
zypper --non-interactive in php5-gd
pecl install svn
echo "extension=svn.so" > /etc/php5/conf.d/svn.ini
rcapache2 restart
