document.addEventListener("DOMContentLoaded", function () {
    // Function to update the cart display and calculate the total
    function updateCart() {
      const cartItemsContainer = document.querySelector(".cart-items-container");
      const totalElement = document.getElementById("total");
  
      // Clear the existing cart display
      cartItemsContainer.innerHTML = "";
  
      // Retrieve the cart from sessionStorage
      const cart = JSON.parse(sessionStorage.getItem("cart")) || [];
  
      // Calculate and update the total
      let total = 0;
  
      cart.forEach((product, index) => {
        const cartItem = document.createElement("div");
        cartItem.classList.add("cart-item");
  
        const productName = document.createElement("p");
        productName.classList.add("cart-item-name");
        productName.textContent = product.name;
  
        const productPrice = parseFloat(product.price);
        total += productPrice;
  
        const productPriceElement = document.createElement("p");
        productPriceElement.classList.add("cart-item-price");
        productPriceElement.textContent = `Price: ₹${productPrice.toFixed(2)}`;
  
        const productRemoveButton = document.createElement("button");
        productRemoveButton.textContent = "Remove";
        productRemoveButton.classList.add("remove-button");
        productRemoveButton.addEventListener("click", () => {
          removeFromCart(index);
          updateCart(); // Update the cart display after removal
        });
  
        cartItem.appendChild(productName);
        cartItem.appendChild(productPriceElement);
        cartItem.appendChild(productRemoveButton);
        cartItemsContainer.appendChild(cartItem);
      });
  
      totalElement.textContent = total.toFixed(2);
    }
  
    // Function to remove a product from the cart by index
    function removeFromCart(index) {
      const cart = JSON.parse(sessionStorage.getItem("cart")) || [];
      if (index >= 0 && index < cart.length) {
        cart.splice(index, 1);
        sessionStorage.setItem("cart", JSON.stringify(cart));
      }
    }
  
    // Handle Place Order button click
    const placeOrderButton = document.getElementById("place-order-button");
    placeOrderButton.addEventListener("click", () => {
      const selectedPaymentOption = document.querySelector('input[name="payment"]:checked');
      const addressForm = document.getElementById("address-form");
  
      if (selectedPaymentOption && addressForm.checkValidity()) {
        // Perform order placement logic here, e.g., submit the order to a server.
  
        alert("Order placed successfully!");
      } else {
        alert("Please select a payment option and fill in the address form.");
      }
    });
  
    // Initial cart update
    updateCart();
    // Function to show/hide card details form
 // Function to show/hide card details form
 function toggleCardDetailsForm() {
  const cardDetailsForm = document.getElementById("card-details-form");
  const paymentOptionCard = document.getElementById("payment-option-card");

  if (paymentOptionCard.checked) {
    cardDetailsForm.style.display = "block";
  } else {
    cardDetailsForm.style.display = "none";
  }
}

// Function to show/hide UPI details form
function toggleUpiDetailsForm() {
  const upiDetailsForm = document.getElementById("upi-details-form");
  const paymentOptionUpi = document.querySelector('input[name="payment"][value="upi"]');

  if (paymentOptionUpi.checked) {
    upiDetailsForm.style.display = "block";
  } else {
    upiDetailsForm.style.display = "none";
  }
}

// Listen for changes in the selected payment option
const paymentOptions = document.querySelectorAll('input[name="payment"]');
paymentOptions.forEach((option) => {
  option.addEventListener("change", function () {
    toggleCardDetailsForm();
    toggleUpiDetailsForm();
  });
});

// Initial state
toggleCardDetailsForm();
toggleUpiDetailsForm();
  });
  