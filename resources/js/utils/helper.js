export function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

export function formatDate(date) {
    const d = new Date(date);
    
    // Format tahun, bulan, dan hari
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0'); // Tambahkan 1 pada bulan karena bulan dimulai dari 0
    const day = String(d.getDate()).padStart(2, '0'); // Tambahkan leading zero jika perlu

    return `${year}-${month}-${day}`;
}

export function searchProducts(products, searchQuery) {
    if (!searchQuery) return products;
    const lowerCaseQuery = searchQuery.toLowerCase();
  
    return products.filter(product =>
      product.name.toLowerCase().includes(lowerCaseQuery) ||
      product.category.name.toLowerCase().includes(lowerCaseQuery) ||
      product.description.toLowerCase().includes(lowerCaseQuery)
    );
  }