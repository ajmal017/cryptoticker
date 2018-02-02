<?php
/**
 * Stock Ticker General Settings page template
 *
 * @category Template
 * @package Stock Ticker
 * @author Aleksandar Urosevic
 * @license https://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link https://urosevic.net
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpau_stockticker;
?>
<div class="wrap" id="stock_ticker_settings">
	<h2><?php echo $wpau_stockticker->plugin_name . ' ' . __( 'Settings', 'wpaust' ); ?></h2>
	<em>Plugin version: <?php echo $wpau_stockticker::VER; ?></em>
	<div class="stock_ticker_wrapper">
		<div class="content_cell">
			<form method="post" action="options.php">
				<?php settings_fields( 'wpaust_general' ); ?>
				<?php settings_fields( 'wpaust_default' ); ?>
				<?php settings_fields( 'wpaust_advanced' ); ?>
				<?php do_settings_sections( $wpau_stockticker->plugin_slug ); ?>
				<?php submit_button(); ?>
			</form>
		</div><!-- .content_cell -->

		<div class="sidebar_container">
			<a href="https://urosevic.net/wordpress/donate/?donate_for=stock-ticker" class="aust-button paypal_donate" target="_blank">Donate</a>
			<br />
			<a href="https://wordpress.org/plugins/stock-ticker/faq/" class="aust-button" target="_blank">FAQ</a>
			<br />
			<a href="https://wordpress.org/support/plugin/stock-ticker" class="aust-button" target="_blank">Community Support</a>
			<h2><?php esc_attr_e( 'Disclaimer', 'wpaust' ); ?></h2>
			<p class="description"><?php
				printf(
				__( 'All data provided by CoinMarketCap.com is displayed for informational and educational purposes only and should not be considered as investment advise. <br />Author of the plugin does not accept liability or responsibility for your use of plugin, including but not limited to trading and investment results.' ),
				__( 'Stock Ticker', 'wpaust' ),
				'1.0.0',
				'<strong>Blockchain.wtf</strong>'
				);
			?></p>

		</div><!-- .sidebar_container -->
	</div><!-- .stock_ticker_wrapper -->

	<div class="help">
		<h2><?php esc_attr_e( 'Help', 'wpaust' ); ?></h2>
		<p><?php printf( esc_attr__( 'You also can use shortcode %s where:', 'wpaust' ), '<code>[stock_ticker symbols="" show="" number_format="" decimals="" static="" speed="" class=""]</code>' ); ?>
			<ul>
				<li><code><strong>symbols</strong></code> <?php esc_attr_e( 'represent array of stock symbols (default from this settings page used if no custom set by shortcode)', 'wpaust' ); ?></li>
				<li><code><strong>show</strong></code> <?php printf( esc_attr__( 'can be %1$s to represent company with Company Name (default), or %2$s to represent company with Stock Symbol', 'wpaust' ), '<code>name</code>', '<code>symbol</code>' ); ?></li>
				<li><code><strong>number_format</strong></code> <?php printf( __( 'override default number format for values (default from this settings page used if no custom set by shortcode). Valid options are: %s and %s', 'wpaust' ), '<code>cd</code> for <em>0.000,00</em>; <code>dc</code> for <em>0,000.00</em>; <code>sd</code> for <em>0 000.00</em>', '<code>sc</code> for <em>0 000,00</em>' ); ?></li>
				<li><code><strong>decimals</strong></code> <?php _e( 'override default number of decimal places for values (default from this settings page used if no custom set by shortcode). Valud values are: 1, 2, 3 and 4', 'wpaust' ); ?></li>
				<li><code><strong>static</strong></code> <?php printf( esc_attr__( 'disables scrolling ticker and makes it static if set to %1$s or %2$s', 'wpaust' ), '<code>1</code>', '<code>true</code>' ); ?></li>
				<li><code><strong>prefill</strong></code> <?php printf( esc_attr__( 'to start with pre-filled instead empty ticker set to %1$s or %2$s', 'wpaust' ), '<code>1</code>', '<code>true</code>' ); ?></li>
				<li><code><strong>duplicate</strong></code> <?php printf( esc_attr__( 'if there is less items than visible on the ticker, set this to %1$s or %2$s to make it continuous', 'wpaust' ), '<code>1</code>', '<code>true</code>' ); ?></li>
				<li><code><strong>speed</strong></code> <?php echo esc_attr__( 'define speed of ticker rendered by shortcode block, different that default speed set on this global settings page', 'wpaust' ); ?></li>
				<li><code><strong>class</strong></code> <?php echo esc_attr__( 'to customize block look and feel set custom CSS class (optional)', 'wpaust' ); ?></li>

			</ul>
		</p>

		<h2><?php esc_attr_e( 'All Data from CoinMarketCap.com - Supported Tokens:', 'wpaust' ); ?></h2>
		<ul>
			<?php
				$supported = ['BTC - Bitcoin','ETH - Ethereum','XRP - Ripple','BCH - Bitcoin Cash','ADA - Cardano','XLM - Stellar','NEO - NEO','LTC - Litecoin','EOS - EOS','XEM - NEM','MIOTA - IOTA','DASH - Dash','XMR - Monero','TRX - TRON','LSK - Lisk','VEN - VeChain','QTUM - Qtum','ETC - Ethereum Classic','USDT - Tether','XRB - Nano','ICX - ICON','PPT - Populous','BTG - Bitcoin Gold','OMG - OmiseGO','ZEC - Zcash','STEEM - Steem','BTS - BitShares','STRAT - Stratis','BNB - Binance Coin','BCN - Bytecoin','SC - Siacoin','XVG - Verge','MKR - Maker','ZRX - 0x','VERI - Veritaseum','DGD - DigixDAO','WTC - Walton','REP - Augur','WAVES - Waves','KCS - KuCoin Shares','SNT - Status','RHOC - RChain','AE - Aeternity','DCR - Decred','DOGE - Dogecoin','ARDR - Ardor','HSR - Hshare','GAS - Gas','KMD - Komodo','KNC - Kyber Network','ZIL - Zilliqa','BAT - Basic Attention Token','DRGN - Dragonchain','LRC - Loopring','ARK - Ark','DGB - DigiByte','ELF - aelf','ETN - Electroneum','IOST - IOStoken','QASH - QASH','PIVX - PIVX','NAS - Nebulas','ZCL - ZClassic','PLR - Pillar','BTM - Bytom','GBYTE - Byteball Bytes','GNT - Golem','DCN - Dentacoin','CNX - Cryptonex','CND - Cindicator','ETHOS - Ethos','FUN - FunFair','R - Revain','AION - Aion','SALT - SALT','SYS - Syscoin','FCT - Factom','GXS - GXShares','BTX - Bitcore','POWR - Power Ledger','DENT - Dent','AGI - SingularityNET','XZC - ZCoin','SMART - SmartCash','NXT - Nxt','IGNIS - Ignis','REQ - Request Network','MAID - MaidSafeCoin','KIN - Kin','RDD - ReddCoin','NXS - Nexus','ENG - Enigma','BNT - Bancor','MONA - MonaCoin','PAY - TenX','ICN - Iconomi','PART - Particl','GNO - Gnosis','WAX - WAX','NEBL - Neblio'];

			foreach ( $supported as $symbol ) {
				printf(
					'<li><strong>%1$s</strong></li>',
					$symbol
				);
			}
			?>
		</ul>
	</div><!-- .help_cell -->
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('.wpau-color-field').wpColorPicker();
});
</script>
