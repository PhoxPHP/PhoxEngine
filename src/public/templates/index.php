#parent<"layout">

#viewAs<"content">

	#set<app, "phoxengine">

	#each<$results>
		
		<h1>This data</h1>

		#php<print_r($app)>

	#stopEach

	#include<"partial">
#stopView