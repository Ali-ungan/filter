<?php
/**
 * WordPress için başlangıç ayar dosyası.
 *
 * Bu dosya kurulum sırasında wp-config.php dosyasının oluşturulabilmesi için
 * kullanılır. İsterseniz bu dosyayı kopyalayıp, ismini "wp-config.php" olarak değiştirip,
 * değerleri girerek de kullanabilirsiniz.
 *
 * Bu dosya şu ayarları içerir:
 * 
 * * MySQL ayarları
 * * Gizli anahtarlar
 * * Veritabanı tablo ön eki
 * * ABSPATH
 * 
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL ayarları - Bu bilgileri servis sağlayıcınızdan alabilirsiniz ** //
/** WordPress için kullanılacak veritabanının adı */
define( 'DB_NAME', 'filter' );

/** MySQL veritabanı kullanıcısı */
define( 'DB_USER', 'root' );

/** MySQL veritabanı parolası */
define( 'DB_PASSWORD', '' );

/** MySQL sunucusu */
define( 'DB_HOST', 'localhost' );

/** Yaratılacak tablolar için veritabanı karakter seti. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Veritabanı karşılaştırma tipi. Herhangi bir şüpheniz varsa bu değeri değiştirmeyin. */
define( 'DB_COLLATE', '' );

/**#@+
 * Eşsiz doğrulama anahtarları ve tuzlar.
 *
 * Her anahtar farklı bir karakter kümesi olmalı!
 * {@link http://api.wordpress.org/secret-key/1.1/salt WordPress.org secret-key service} servisini kullanarak yaratabilirsiniz.
 * Çerezleri geçersiz kılmak için istediğiniz zaman bu değerleri değiştirebilirsiniz. Bu tüm kullanıcıların tekrar giriş yapmasını gerektirecektir.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'KrfwGZ-46}J%V5#3}`H:;+[wo]^S)V(cB- Utc;+B~<n`~B.2yMrIpego@x!QC:P' );
define( 'SECURE_AUTH_KEY',  '93HSY&N&(QOIf/T![QP0e$0>6TpAm=6S5.:,?<djducTu{Yp;b,g}+Sp4mcj^!1J' );
define( 'LOGGED_IN_KEY',    'PWUJ&~>2H((nkWn`6{f~7Hrz8sITCn<m}<O<Y2m:OW}.8T]|,&!.YgTrl$k)T:K{' );
define( 'NONCE_KEY',        '#`LSJmDRB9HxIB>+z]@s2D[$9(6$N,&|0;Jh(KTPE>B0Le^<J!>OtkGZVU<?$Uq ' );
define( 'AUTH_SALT',        '(kK{Tn_[=`x;SazA7.>VP3_=dc(IL[p^43$V{$I150/d@@h_0/{0_ow(*y0,!b+$' );
define( 'SECURE_AUTH_SALT', '&u647JMc2<}Z|7^rsaFun0ytT5caBrNZ(GjXg)^EzYa+E:5&G2qEO[3}DK!*Huy{' );
define( 'LOGGED_IN_SALT',   'wtv?~k7f45@bY,hu#jF,E>%=k8zo~>c26fJi5G9rRP82nJg8KtD=:i8$[*9bcA&L' );
define( 'NONCE_SALT',       'oE ; s_UvzhrVE}/yeTesCq5EC3-jbJRyyJ8 Y%URr8C>[l`tl~9,_X`!PgDXkQs' );

/**#@-*/

/**
 * WordPress veritabanı tablo ön eki.
 *
 * Tüm kurulumlara ayrı bir önek vererek bir veritabanına birden fazla kurulum yapabilirsiniz.
 * Sadece rakamlar, harfler ve alt çizgi lütfen.
 */
$table_prefix = 'wp_';

/**
 * Geliştiriciler için: WordPress hata ayıklama modu.
 *
 * Bu değeri "true" yaparak geliştirme sırasında hataların ekrana basılmasını sağlayabilirsiniz.
 * Tema ve eklenti geliştiricilerinin geliştirme aşamasında WP_DEBUG
 * kullanmalarını önemle tavsiye ederiz.
 * 
 * Hata ayıklama için kullanabilecek diğer sabitler ile ilgili daha fazla bilgiyi
 * belgelerden edinebilirsiniz.
 * 
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Hepsi bu kadar. Mutlu bloglamalar! */

/** WordPress dizini için mutlak yol. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** WordPress değişkenlerini ve yollarını kurar. */
require_once ABSPATH . 'wp-settings.php';