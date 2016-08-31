var mainApp = angular.module('mainApp', []);

mainApp.controller('tableCtrl', function($scope, $http){
	
	$scope.ajaxUrl = '/tabelle/public_html/lib/ajax/send_ajax.php'
	
	$scope.sortTable = "id";
	
	var toGet= {"action": "get_data"};
	var toGet = JSON.stringify(toGet);
	
	$http({
			method: 'POST',
			url: $scope.ajaxUrl,
			data: "data=" + toGet,
			headers: {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
		})
		.then(function(response){
			
			$scope.projects = response.data['projekte'];
						
			for(i = 0; i < $scope.projects.length; i++){
				$scope.projects[i].nr = i;
				$scope.projects[i].id = parseInt($scope.projects[i].id);
				$scope.projects[i].summe = parseInt($scope.projects[i].summe);
			}
			
			console.log(response);
			
		}, function(response){
			alert('error');
		});

/* ========================================================================= */		

	/**
	 * shows input row for editing entry
	*/
	$scope.openEditRow = function(projectNr){
					
		var rowId = "modelRow_" + projectNr;
		document.getElementById(rowId).className = "modelRow";
		
		var editBtnId = "editBtn_" + projectNr;
		document.getElementById(editBtnId).className = "hidden";
		
		var deleteBtnId = "deleteBtn_" + projectNr;
		document.getElementById(deleteBtnId).className = "";
			
	}

/* ========================================================================= */	
	
	/**
	 * closes edit input row
	*/
	$scope.closeEditRow = function(projectNr){
		
		var rowId = "modelRow_" + projectNr;
		document.getElementById(rowId).className = "hidden";
		
		var editBtnId = "editBtn_" + projectNr;
		document.getElementById(editBtnId).className = "";
		
		var deleteBtnId = "deleteBtn_" + projectNr;
		document.getElementById(deleteBtnId).className = "hidden";
			
	}
	
/* ========================================================================= */		
	
	/**
	 * shows input row for new entry
	*/
	$scope.openAddEntry = function(){
				
		var rowId = "newEntryRow";
		document.getElementById(rowId).className = "";
	}
	
/* ========================================================================= */	
	
	/**
	 * saves entry after editing
	 */
	$scope.saveEntry = function(projectNr){
		
		var toSend = JSON.stringify(this.projects[projectNr]);
		toSend = toSend.replace('&', '%26');
		
		var post_array = {
				
				"action": "store_data",
				"content": toSend,
		};
		
		var post_string = JSON.stringify(post_array);
		
		$http({
			method: 'POST',
			url: this.ajaxUrl,
			data: "data=" + post_string,
			headers: {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
		}).then(function(response){
			alert('Eintrag wurde aktualisiert');
			
			$scope.closeEditRow(projectNr);
			
		}, function(response){
			alert('error');
			console.log(response);
		}); 
			
	}
	
/* ========================================================================= */	

	/**
	 * adds new entry to database
	*/
	$scope.addEntry = function(){
				
		this.newProject = {
				'jahre': this.newEntry.jahre,
				'projekt': this.newEntry.projekt,
				'beschreibung': this.newEntry.beschreibung,
				'summe': this.newEntry.summe	
		};
		
		var toAdd = JSON.stringify(this.newEntry);
		toAdd = toAdd.replace('&', '%26');
		
		var post_array = {
				
				"action": "add_entry",
				"content": toAdd,
		};
		
		var post_string = JSON.stringify(post_array);
		
		$http({
			method: 'POST',
			url: this.ajaxUrl,
			data: "data=" + post_string,
			headers: {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
		}).then(function(response){
			alert("Neuer Eintrag hinzugefügt");
			console.log(response.data);
			location.reload();
			
		}, function(response){
			alert('Bei der Eingabe ist ein Fehler aufgetreten');
			console.log(response.data);
		}); 
			
		
	}
	
/* ========================================================================= */	

	/**
	 * deletes an entry from database and $scope
	*/
	$scope.deleteEntry = function(nr, id){
		
		if(confirm("Diesen Eintrag wirklich endgültig löschen?")){
			
			toDelete = {"id": id};
		
			var post_array = {
					
					"action": "delete_entry",
					"content": toDelete,
			};
			
			var post_string = JSON.stringify(post_array);
			
			$http({
				method: "POST",
				url: this.ajaxUrl,
				data: "data=" + post_string,
				headers: {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
			}).then(function(response){
				alert(response.data);
	
				$scope.projects.splice(nr, 1);
				
			}, function(response){
				alert("Beim Löschen ist ein Fehler aufgetreten");
				console.log(response.data);
			});
		}
		else{
			$scope.closeEditRow(nr);
		}
		
	}

/* ========================================================================= */	
	
	/**
	 * calculates the total
	 */
	$scope.getTotal = function(){
		
		var total = 0;
		var projectList = $scope.projects.length;
		
		for(i = 0; i < projectList; i++){
			
			var singleProject = $scope.projects[i];
			
			var amount = parseInt(singleProject.summe);
			
			total += amount; 
		}
	
		return total;
	}
	
});