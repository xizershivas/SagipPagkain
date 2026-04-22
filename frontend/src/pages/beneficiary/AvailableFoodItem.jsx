import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { inventoryService } from '../../services/api';

export default function AvailableFoodItem() {
  const [inventory, setInventory] = useState([]);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [filter, setFilter] = useState('strCategory');
  const [page, setPage] = useState(1);
  const limit = 12;

  const load = () => {
    setLoading(true);
    inventoryService.getAll({ search, filter, page, limit })
      .then(r => { setInventory(r.data.data); setTotal(r.data.totalRecords); })
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, [page]);

  const totalPages = Math.ceil(total / limit);

  const categoryColors = {
    Vegetables: '#28a745', Fruits: '#fd7e14', 'Grains & Cereals': '#ffc107',
    Dairy: '#17a2b8', 'Meat & Poultry': '#dc3545', 'Fish & Seafood': '#0d6efd',
    'Canned Goods': '#6f42c1', 'Bread & Bakery': '#d63384', Beverages: '#20c997', default: '#6c757d'
  };

  return (
    <DashboardLayout title="Available Food Items">
      <div className="form-card mb-3">
        <form className="d-flex gap-2" onSubmit={e => { e.preventDefault(); setPage(1); load(); }}>
          <select className="form-select form-select-sm" style={{ width: 150 }} value={filter} onChange={e => setFilter(e.target.value)}>
            <option value="strCategory">Category</option>
            <option value="strItem">Item</option>
            <option value="strFoodBankName">Food Bank</option>
          </select>
          <input type="text" className="form-control form-control-sm" placeholder="Search available food..."
            value={search} onChange={e => setSearch(e.target.value)} />
          <button type="submit" className="btn btn-sm btn-primary-sp">Search</button>
        </form>
      </div>

      {loading ? (
        <div className="text-center py-5"><div className="spinner-border" style={{ color: 'var(--primary)' }}></div></div>
      ) : inventory.length === 0 ? (
        <div className="text-center py-5 text-muted">
          <i className="bi bi-basket2 fs-1 d-block mb-3 opacity-25"></i>
          <p>No food items available at this time</p>
        </div>
      ) : (
        <>
          <div className="row g-3">
            {inventory.map(item => {
              const color = categoryColors[item.strCategory] || categoryColors.default;
              const today = new Date();
              const expiry = new Date(item.dtmExpirationDate);
              const daysLeft = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));
              return (
                <div key={item.intInventoryId} className="col-md-4 col-lg-3">
                  <div className="card border-0 shadow-sm h-100" style={{ borderRadius: 10, overflow: 'hidden' }}>
                    <div style={{ background: color, height: 6 }}></div>
                    <div className="card-body p-3">
                      <div className="d-flex justify-content-between align-items-start mb-2">
                        <h6 className="fw-bold mb-0 small">{item.strItem}</h6>
                        <span className="badge" style={{ background: color, fontSize: '0.7rem' }}>{item.strCategory}</span>
                      </div>
                      <div className="text-muted small mb-2">
                        <i className="bi bi-building me-1"></i>{item.strFoodBankName}
                      </div>
                      <div className="d-flex justify-content-between align-items-center">
                        <div>
                          <span className="fw-bold" style={{ color: 'var(--primary)', fontSize: '1.2rem' }}>{item.intQuantity}</span>
                          <span className="text-muted small ms-1">{item.strUnit}</span>
                        </div>
                        <span className={`badge-status ${daysLeft <= 7 ? 'badge-rejected' : daysLeft <= 30 ? 'badge-pending' : 'badge-approved'}`}>
                          {daysLeft <= 0 ? 'Expired' : `${daysLeft}d left`}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
          <div className="d-flex justify-content-between align-items-center mt-3">
            <span className="text-muted small">{total} items available</span>
            <div className="d-flex gap-2">
              <button className="pagination-btn" onClick={() => setPage(p => p - 1)} disabled={page <= 1}>
                <i className="bi bi-chevron-left"></i> Prev
              </button>
              <span className="small d-flex align-items-center">Page {page}/{totalPages || 1}</span>
              <button className="pagination-btn" onClick={() => setPage(p => p + 1)} disabled={page >= totalPages}>
                Next <i className="bi bi-chevron-right"></i>
              </button>
            </div>
          </div>
        </>
      )}
    </DashboardLayout>
  );
}
