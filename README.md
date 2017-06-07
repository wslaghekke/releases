releases
========

A Symfony project created on May 27, 2017, 11:39 am.

Setup
-----

Generate keys for jwt signing
```bash
bin/generate_keys.sh
```

Set acl's for apache (commands only tested on Centos 7)
```bash
sudo setfacl -R -m u:apache:rwx var/cache/ var/logs/ var/sessions/
sudo setfacl -dR -m u:apache:rwx var/cache/ var/logs/ var/sessions/
sudo chcon -R -t public\_content\_rw\_t var/cache/ var/logs/ var/sessions/
```

Other install commands:
```bash
composer install
bin/console d:m:m
```
