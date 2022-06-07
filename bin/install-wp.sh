#!/usr/bin/env bash

if [ $# -lt 2 ]; then
	echo "usage: $(basename $0) <path> [version]"
	exit 1
fi

WP_PATH=$1
WP_VERSION=${2-latest}

download() {
    if [ `which curl` ]; then
        curl -Ls "$1" > "$2";
    elif [ `which wget` ]; then
        wget -nv -O "$2" "$1"
    fi
}

install_wp() {
	mkdir -p $WP_PATH

	local FORCE_DOWNLOAD='false'
	local ARCHIVE_NAME=''
	local DOWNLOAD_URL=''

	if [ $WP_VERSION == 'latest' ]; then
		FORCE_DOWNLOAD='true'
		ARCHIVE_NAME='wordpress-latest'
		DOWNLOAD_URL="https://wordpress.org/latest.tar.gz"
	elif [[ $WP_VERSION =~ [0-9]+\.[0-9]+ ]]; then
		# https serves multiple offers, whereas http serves single.
		download https://api.wordpress.org/core/version-check/1.7/ $TMPDIR/wp-latest.json
		if [[ $WP_VERSION =~ [0-9]+\.[0-9]+\.[0] ]]; then
			# version x.x.0 means the first release of the major version, so strip off the .0 and download version x.x
			LATEST_VERSION=${WP_VERSION%??}
		else
			# otherwise, scan the releases and get the most up to date minor version of the major release
			local VERSION_ESCAPED=`echo $WP_VERSION | sed 's/\./\\\\./g'`
			LATEST_VERSION=$(grep -o '"version":"'$VERSION_ESCAPED'[^"]*' $TMPDIR/wp-latest.json | sed 's/"version":"//' | head -1)
		fi
		if [[ -z "$LATEST_VERSION" ]]; then
			ARCHIVE_NAME="wordpress-$WP_VERSION"
		else
			ARCHIVE_NAME="wordpress-$LATEST_VERSION"
		fi
		DOWNLOAD_URL="https://wordpress.org/${ARCHIVE_NAME}.tar.gz"
	else
		ARCHIVE_NAME="wordpress-$WP_VERSION"
		DOWNLOAD_URL="https://wordpress.org/${ARCHIVE_NAME}.tar.gz"
	fi

	if [[ 'true' == $FORCE_DOWNLOAD || ! -f ${TMPDIR}/${ARCHIVE_NAME}.tar.gz ]]; then
		download $DOWNLOAD_URL ${TMPDIR}/${ARCHIVE_NAME}.tar.gz
	fi

	tar --strip-components=1 --exclude=wp-content --exclude=wp-config-sample.php -zxmf ${TMPDIR}/${ARCHIVE_NAME}.tar.gz -C $WP_PATH
}

install_wp
