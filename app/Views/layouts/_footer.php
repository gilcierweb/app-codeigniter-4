<footer class="container mt-6 w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto ">
		<!-- Grid -->
		<div class="text-center mx-auto w-full max-w-screen-xl">
			<div>
				<a class="flex-none text-xl font-semibold text-black dark:text-white" href="/" aria-label="Brand">App CodeIgniter 4</a>
			</div>
			<!-- End Col -->

			<div class="mt-3">
				<p class="text-gray-500 dark:text-neutral-500">
                &copy; <?= date('Y') ?> App CodeIgniter 4.
				</p>
				<p class="text-gray-500 dark:text-neutral-500">Copyright &copy; <?= date('Y') ?> App CodeIgniter - All
					Rights Reserved - Desenvolvido por: <a href="https://gilcierweb.com.br/gilcierweb" title="GilcierWeb" class="link link-primary link-animated">GilcierWeb</a> -
					Site criado com o Framework <a href="https://CodeIgniter.com" title="CodeIgniter 4" target="_blank" class="link link-primary link-animated">CodeIgniter 4</a>
				</p>
				<p class="text-gray-500 dark:text-neutral-500">Page rendered in <strong>{elapsed_time}</strong>
					seconds. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
				</p>
                <div class="environment">
                    <p>Page rendered in {elapsed_time} seconds using {memory_usage} MB of memory.</p>
                    <p>Environment: <?= ENVIRONMENT ?></p>
                </div>

			</div>

			<!-- Social Brands -->
			<div class="mt-3 space-x-2">
				<p class="copyright-text">Site melhor visualizado com navegadores modernos como:
					<a href="https://browsehappy.com/" title="Mozilla Firefox" target="_blank" class="link link-primary link-animated">Firefox</a>,
					<a href="https://www.opera.com/" title="Opera" target="_blank" class="link link-primary link-animated">Opera</a>,
					<a href="https://browsehappy.com/" title="Google Chrome" target="_blank" class="link link-primary link-animated">Google Chrome</a>,
					<a href="https://browsehappy.com/" title="Apple Safari" target="_blank" class="link link-primary link-animated">Apple Safari</a>,
					<a href="https://browsehappy.com/" title="Brave" target="_blank"  class="link link-primary link-animated">Brave</a>,
					<a href="https://browsehappy.com/" title="Vivaldi" target="_blank"  class="link link-primary link-animated">Vivaldi</a>
				</p>
			</div>
			<!-- End Social Brands -->
		</div>
		<!-- End Grid -->
	</footer>