
document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".fade-slide-in");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate");
            }
        });
    }, {
        threshold: 0.1
    });

    elements.forEach(el => observer.observe(el));
});



document.addEventListener('DOMContentLoaded', function () {
    const alertBox = document.getElementById('success-alert');

    if (alertBox) {
        // بدء الظهور بالأنيميشن
        alertBox.style.animation = 'slideFadeIn 0.8s ease-out forwards';

        // بعد 2 ثانية يبدأ يختفي
        setTimeout(() => {
            alertBox.style.animation = 'slideFadeOut 0.8s ease-out forwards';
            setTimeout(() => {
                alertBox.remove();
            }, 1000); // وقت الانيميشن
        }, 2000);
    }
});




// AJAX


document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('.status-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const userId = this.dataset.userId;
            const status = this.checked ? 'active' : 'inactive';

            fetch('/user/toggle-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: status
                })
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => { throw error; });
                    }
                    return response.json();
                })
                .then(data => {
                    showToast('Status updated successfully');
                })
                .catch(error => {
                    console.error(error);
                    showToast('Error updating status', true);
                });
        });
    });

    // ✅ Toast Function
    function showToast(message, isError = false) {
        const toast = document.createElement('div');
        toast.className = `custom-toast ${isError ? 'error' : 'success'}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 1500); // 0.5 second
    }
});





// Create Invoice
window.addEventListener('DOMContentLoaded', function () {
    const typeExisting = document.getElementById('type_existing');
    const typeWalkin = document.getElementById('type_walkin');
    const customerLabel = document.getElementById('customer_label');
    const userSelect = document.getElementById('customer_id');
    const walkinInput = document.getElementById('customer_name');



    function toggleCustomerFields() {
        if (typeExisting.checked) {
            customerLabel.innerText = "Select Customer *";
            userSelect.classList.remove('d-none');
            walkinInput.classList.add('d-none');
            walkinInput.value = '';
        } else if (typeWalkin.checked) {
            customerLabel.innerText = "Add Customer *";
            userSelect.classList.add('d-none');
            walkinInput.classList.remove('d-none');
            userSelect.value = '';
        }
    }

    typeExisting.addEventListener('change', toggleCustomerFields);
    typeWalkin.addEventListener('change', toggleCustomerFields);
    toggleCustomerFields();
});

let rowIndex = 0;


function addProductRow() {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${getProductSelectHtml(rowIndex)}</td>
        <td>
            <input type="number" name="products[${rowIndex}][quantity]" class="form-control quantity" value="0" min="0"
                oninput="checkStockLimit(this)" required>
            <small class="text-danger stock-warning d-none"></small>
        </td>
        <td><input type="text" name="products[${rowIndex}][unit_price]" class="form-control unit-price" readonly></td>
        <td><input type="text" name="products[${rowIndex}][total_price]" class="form-control total-price" readonly></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
    `;
    document.getElementById('products-table').appendChild(row);
    rowIndex++;
    updateInvoiceTotal();
}


function checkStockLimit(inputElement) {
    const row = inputElement.closest('tr');
    const selectElement = row.querySelector('.product-select');
    const productId = selectElement.value;
    const warningElement = row.querySelector('.stock-warning');
    const invoiceType = document.getElementById('type').value;  // Get current invoice type

    if (productId && productData[productId]) {
        const maxStock = productData[productId].stock_quantity;
        let qty = parseInt(inputElement.value) || 0;

        if (invoiceType === 'sale') {
            if (qty > maxStock) {
                inputElement.value = maxStock;
                warningElement.innerText = `Only ${maxStock} units available in stock.`;
                warningElement.classList.remove('d-none');
            } else {
                warningElement.classList.add('d-none');
            }
        } else {
            // For Purchase, no restriction
            warningElement.classList.add('d-none');
        }

        updateRowTotal(inputElement);
    }
}





function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    updateInvoiceTotal();
}

function isProductDuplicate(productId) {
    const selects = document.querySelectorAll('.product-select');
    let count = 0;
    selects.forEach(s => {
        if (s.value == productId) count++;
    });
    return count > 1;
}

// This function generates the HTML for the product select dropdown
function getProductSelectHtml(rowIndex) {
    let options = `<option value="">-- Select Product --</option>`;
    for (const [id, data] of Object.entries(productData)) {
        options += `<option value="${id}">${data.name}</option>`;
    }

    return `<select name="products[${rowIndex}][product_id]" class="form-control product-select" onchange="updateUnitPrice(this)" required>${options}</select>`;
}



function updateUnitPrice(select) {
    const selectedProduct = select.value;
    const row = select.closest('tr');

    if (isProductDuplicate(selectedProduct)) {
        alert("This product is already selected.");
        select.value = "";
        return;
    }

    const unitPriceInput = row.querySelector('.unit-price');
    unitPriceInput.value = productData[selectedProduct]?.sale_price || 0;

    // Reset quantity to 0
    const qtyInput = row.querySelector('.quantity');
    qtyInput.value = 0;
    row.querySelector('.stock-warning').classList.add('d-none');

    updateRowTotal(select);
    updateInvoiceTotal();
}




function updateRowTotal(element) {
    const row = element.closest('tr');
    const qty = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const total = qty * unitPrice;
    row.querySelector('.total-price').value = total.toFixed(2);
    updateInvoiceTotal();
}
function handleDiscountChange() {
    const discountInput = document.getElementById('discount');
    let value = parseFloat(discountInput.value);

    // تأكد من أن القيمة ليست سالبة
    if (value < 0) {
        discountInput.value = 0;
        value = 0;
    }

    updateInvoiceTotal();
}

function updateInvoiceTotal() {
    let total = 0;
    document.querySelectorAll('.total-price').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const taxRate = parseFloat(document.getElementById('tax').value) || 0;

    let totalWithDiscount = total - discount;
    if (totalWithDiscount < 0) totalWithDiscount = 0;

    const tax = (totalWithDiscount * taxRate) / 100;
    const finalTotal = totalWithDiscount + tax;

    document.getElementById('total_amount').value = finalTotal.toFixed(2);
}


const invoiceType = document.getElementById('type');
const customerTypeGroup = document.getElementById('customer_type_group');
const customerGroup = document.getElementById('customer_dynamic_group');
const supplierGroup = document.getElementById('supplier_group');

function toggleTypeBehavior() {
    if (invoiceType.value === 'purcash') {
        // إخفاء Customer Type
        customerTypeGroup.classList.add('d-none');

        // إخفاء حقل العملاء
        customerGroup.classList.add('d-none');

        // عرض حقل الموردين
        supplierGroup.classList.remove('d-none');
    } else {
        // إظهار Customer Type
        customerTypeGroup.classList.remove('d-none');

        // إظهار حقل العملاء
        customerGroup.classList.remove('d-none');

        // إخفاء حقل الموردين
        supplierGroup.classList.add('d-none');
    }
}

invoiceType.addEventListener('change', toggleTypeBehavior);
toggleTypeBehavior(); // تشغيل أولي عند تحميل الصفحة




















