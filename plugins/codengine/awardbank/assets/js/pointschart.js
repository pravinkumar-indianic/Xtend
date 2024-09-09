

function credittransgraph(pointcredits, pointtrans){

	/**
	var ctx2 = document.getElementById("myChart2").getContext('2d');

	var myDoughnutChart = new Chart(ctx2, {
	    type: 'doughnut',
	    data: {
	        datasets: [{
	            data: [pointcredits, pointtrans],
	            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)']
	        }],
	        labels: [
		        'Credit',
		        'Transaction'
		    ]
	    },
	    options: {
	        tooltips: {
	            callbacks: {
			        title: function(tooltipItems, data) {
			          return '';
			        },
			        label: function(tooltipItem, data) {
			          var datasetLabel = '';
			          var label = data.labels[tooltipItem.index];
			          return data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
			        }
			    }
	        }
	    }
	});
	**/
}

function usertofrompointgraph(pointsfromotheruser, pointstootheruser) {

	/**
	var ctx3 = document.getElementById("myChart3").getContext('2d');
	var myDoughnutChart = new Chart(ctx3, {
	    type: 'doughnut',
	    data: {
	        datasets: [{
	            data: [pointsfromotheruser, pointstootheruser],
	            backgroundColor: ['rgba(255, 99, 100, 0.2)', 'rgba(154, 100, 35, 0.2)']
	        }],
	        labels: [
		        'Points from other user',
		        'Points give to other user'
		    ]
	    },
	    options: {
	        tooltips: {
	            callbacks: {
			        title: function(tooltipItems, data) {
			          return '';
			        },
			        label: function(tooltipItem, data) {
			          var datasetLabel = '';
			          var label = data.labels[tooltipItem.index];
			          return data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
			        }
			    }
	        }
	    }
	});

	**/
}

function initPointsGraph(months, sums){

	var ctx = document.getElementById("myChart").getContext('2d');

	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: months,
	        datasets: [{
	            label: 'Point Allocation By Months',
	            data: sums,
	            backgroundColor: 'rgba(244, 181, 124, 0.8)',
	            borderWidth: 0,
	            pointRadius: 0,
	        }]
	    },
	    options: {
	        scales: {
	        	xAxes: [{
					gridLines: {
					  drawOnChartArea: true
					},
					stacked: true
	        	}],
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                },
	                stacked: true
	            }]
	        }
	    }
	});

}