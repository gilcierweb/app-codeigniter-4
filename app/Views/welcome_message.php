<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="author" content="GilcierWeb - Web Developer - gilcierweb@gmail.com - gilcier06@yahoo.com.br - Sites, Sistemas para Web, E-commerce, Manutenção de Sites, Apps Mobile. gilcierweb.com.br" />

	<title>App CodeIgniter 3</title>

	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

	<style type="text/tailwindcss">
		.link {
		--link-color: color-mix(in oklab, var(--color-base-content) 80%, #0000);
		@apply inline-block cursor-pointer font-medium underline;
		color: var(--link-color);
		&:hover {
			color: color-mix(in oklab, var(--link-color) 80%, #000);
		}
		&:focus {
			@apply outline-none;
		}
		&:focus-visible {
			outline: 2px solid currentColor;
			outline-offset: 2px;
		}
		&.disabled,
		&[disabled],
		&:disabled {
			@apply pointer-events-none opacity-50;
		}
		&.link-hover {
			@apply no-underline [@media(hover:hover)]:hover:underline;
		}
		&.link-animated {
			@apply relative no-underline before:pointer-events-none before:absolute before:start-0 before:bottom-0 before:h-px before:w-full before:bg-current before:transition-transform before:duration-300 before:ease-in-out before:content-[''];
		}
		&.link-animated::before {
			transform-origin: 100% 50%;
			transform: scale3d(0, 1, 1);
		}
		&.link-animated:hover::before {
			transform-origin: 0% 50%;
			transform: scale3d(1, 1, 1);
		}
		}

		.link-primary {
		--link-color: var(--color-primary);
		}
		.link-secondary {
		--link-color: var(--color-secondary);
		}
		.link-accent {
		--link-color: var(--color-accent);
		}
		.link-neutral {
		--link-color: var(--color-neutral);
		}
		.link-success {
		--link-color: var(--color-success);
		}
		.link-info {
		--link-color: var(--color-info);
		}
		.link-warning {
		--link-color: var(--color-warning);
		}
		.link-error {
		--link-color: var(--color-error);
		}
		/* custom tailwindcss class */
		.btn-primary-orange {
		@apply block w-full rounded bg-orange-600 px-12 py-3 text-sm font-medium text-white shadow hover:bg-orange-700 focus:outline-none focus:ring active:bg-orange-500 sm:w-auto;
		}
	</style>
</head>

<body class="bg-gray-50">
	<main class="bg-gray-50 h-screen">
		<div class="container mx-auto">

			<section class="bg-gray-50">

				<div class="mx-auto max-w-screen-xl px-4 py-32 lg:flex lg:h-screen lg:items-center">

					<div class="mx-auto  text-center">
						<div class=" flex  items-center justify-center flex-col">
							<img alt="logo" class="mb-10 object-cover object-center rounded"
								src="public/images/logo.png" />
                                <?= img(['src'=> 'images/logo.png','alt' =>'logo', 'class' =>'mb-10 object-cover object-center rounded']); ?>
						</div>
						<h1 class="text-3xl font-extrabold sm:text-5xl">
							App CodeIgniter 4 API. <br>
							<strong class="font-bold text-orange-600 sm:block pt-3"> API RESTful com CodeIgniter 4 </strong>
						</h1>

						<p class="mt-4 sm:text-xl/relaxed">
							Em breve estaremos no ar! </p>

						<div class="mt-8 flex flex-wrap justify-center gap-4">
							
                        <a class="btn-primary-orange"
								href="api/users">
								Conheça
							</a>
						</div>
					</div>
				</div>
			</section>

		</div>
	</main>

<!-- FOOTER: DEBUG INFO + COPYRIGHTS -->


<?= $this->include('layouts/_footer') ?>

<!-- SCRIPTS -->

<script {csp-script-nonce}>
    document.getElementById("menuToggle").addEventListener('click', toggleMenu);
    function toggleMenu() {
        var menuItems = document.getElementsByClassName('menu-item');
        for (var i = 0; i < menuItems.length; i++) {
            var menuItem = menuItems[i];
            menuItem.classList.toggle("hidden");
        }
    }
</script>

<!-- -->

</body>
</html>
