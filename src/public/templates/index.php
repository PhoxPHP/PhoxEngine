#parent<"layout">

#viewAs<"content">

	#set<app, "phoxengine">

	#each<$results>
		<h1>This data</h1>

		#if<$app == ''>
			
			#{"Not available"}

		#elseif<$app !== 'aa'>

			#{$results[$key]}

		#stopIf

		#php<print_r($app)>

	#stopEach

#stopView