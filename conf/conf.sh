#!/bin/bash

conf_apache2="apache2"
conf_certs="certifikati"
conf_db="mysql"

APACHE="/etc/apache2"

# Apache2 mods
function a2enmods() {
    echo "Konfiguracija Apache2 modulov..."
	sudo a2enmod rewrite ssl
    echo
}

# Apache2 sites-available
function a2conf() {
    echo "Konfiguracija strežnika Apache2..."

	for conffile in $conf_apache2/*conf; do

		diff $conffile $APACHE/sites-available/${conffile##*/} >/dev/null
		isdiff=$?

		if (($isdiff)); then
			sudo mv "$APACHE/sites-available/${conffile##*/}" "$APACHE/sites-available/${conffile##*/}.$(date +%Y%m%d_%H%M%S).bak"
			sudo cp "$conffile" "$APACHE/sites-available/"
			echo "Datoteka $conffile kopirana v $APACHE/sites-available/"
		else
            echo "V $conffile ni sprememb."
        fi

        if [[ ! -L $APACHE/sites-enabled/${conffile##*/} ]]; then
            echo "v sites-enabled dodajam symlink na sites-available za ${conffile##*/}"
            sudo ln -s $APACHE/sites-available/${conffile##*/} $APACHE/sites-enabled/${conffile##*/}
        fi
	done
    echo
}

#Changing permissions for file uploading. Add user ep to group www-data. Give ownership to www-data.
function permissions() {
    echo "Nastavljanje dovoljenja za nalaganje slik skupini www-data"
	sudo usermod -a -G www-data ep
	sudo chown www-data:www-data /home/ep/NetBeansProjects/ep-trgovina/php/static/img -R
    echo
}

# Server certificates, CA certificates, CRL
function certs() {
    echo "Konfiguracija strežniških certifikatov..."
	sudo cp $conf_certs/*.pem $APACHE/ssl/
    echo
}

function initdb() {
    echo "Inicializacija podatkovne baze..."
	cd $conf_db
	cat baza.sql data.sql | mysql -uroot -p
	cd ..
    echo
}


echo -e "Ustavljam Apache2...\n\n"
sudo service apache2 stop
if (($#)); then
	for arg in $@; do
		$arg
	done
else
    a2enmods
    a2conf
    permissions
    certs
    initdb
fi
echo -e "\n\nZaganjam apache2...\n"
sudo service apache2 start