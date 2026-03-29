<?php
// Assignment 
/*
After each lesson close everything and open a blank PHP file. Without looking at anything — write the core concept from memory. It doesn't have to be perfect. Just try.

Every time you do this your brain builds a stronger memory trace. After three or four attempts you'll write it without thinking.
*/

/**
A page that reads id from the URL
Validate its an id 
query database 
redirect if not found
*/

						$soldCount = 0;
						foreach($properties as $property) : ?>
					<?php if($property['status'] === 'Available') :?>
						<?php  echo $soldCount ++; ?>
					<?php endif; ?>
				<?php endforeach; ?>

<?php if($property['status'] === 'sold') : ?>
								<p> <?php echo count($property['status']) ;?> </p>
							<?php endif; ?>

				<?php foreach($properties as $property) : ?> 
							<p><?php echo count($properties['status'] === 'sold'); ?></p>
					<?php endforeach; ?>
?>