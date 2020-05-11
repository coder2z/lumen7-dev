<?php

 return Array
	(
	'columns'=>Array
		(
		'user'=>Array
			(
			0=>'id',
			1=>'name',
			2=>'password',
			3=>'email',
			4=>'age'
			)
		),
	'repositories'=>Array
		(
		'UserRepository'=>Array
			(
			'model'=>'App\\Models\\User',
			'repository'=>'App\\Repositories\\UserRepository'
			)
		)
	);