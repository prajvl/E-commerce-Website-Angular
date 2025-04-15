app.controller('adminCtrl', function($scope, $http) {
  // Variable to hold the product to be deleted
  $scope.productToDelete = null;

  // Function to load products
  function loadProducts() {
    $http.get('php/products.php').then(function(response) {
      $scope.products = response.data;
    });
  }

  // Call load products on page load
  loadProducts();

  // Function to show the confirmation modal for deletion
  $scope.showConfirmDelete = function(product) {
    // Set the product to be deleted
    $scope.productToDelete = product;
    // Show the modal
    $('#confirmDeleteModal').modal('show');
  };

  // Function to delete the product
  $scope.deleteProduct = function() {
    if ($scope.productToDelete) {
      console.log("Deleting product with ID:", $scope.productToDelete.id); // Log product ID
      $http.post('php/admin.php', { action: 'delete', id: $scope.productToDelete.id })
        .then(function(response) {
          console.log("Response from server:", response.data); // Log the response
          if (response.data.success) {
            alert('Product deleted successfully!');
            loadProducts(); // Refresh the product list
          } else {
            alert('Error deleting product: ' + response.data.error); // Show detailed error
          }
          // Close the modal
          $('#confirmDeleteModal').modal('hide');
        })
        .catch(function(error) {
          console.error("Error:", error); // Log any network error
          alert('Error deleting product.');
          $('#confirmDeleteModal').modal('hide');
        });
    }
  };
});
