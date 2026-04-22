import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { inventoryService } from '../../services/api';

export default function AdminInventory() {
  const [inventory, setInventory] = useState([]);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [filter, setFilter] = useState('strCategory');
  const [page, setPage] = useState(1);
  const limit = 10;

  const load = () => {
    setLoading(true);
    inventoryService.getAll({ search, filter, page, limit })
      .then(r => { setInventory(r.data.data); setTotal(r.data.totalRecords); })
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, [page]);

  const handleSearch = (e) => { e.preventDefault(); setPage(1); load(); };

  const totalPages = Math.ceil(total / limit);

  return (
    <DashboardLayout title="Inventory Management">
      <div className="data-table-container">
        <div className="data-table-header">
          <h6><i className="bi bi-box-seam me-2" style={{ color: 'var(--primary)' }}></i>Food Inventory</h6>
          <form className="d-flex gap-2" onSubmit={handleSearch}>
            <select className="form-select form-select-sm" style={{ width: 150 }} value={filter}
              onChange={e => setFilter(e.target.value)}>
              <option value="strCategory">Category</option>
              <option value="strItem">Item</option>
              <option value="strUnit">Unit</option>
              <option value="strFoodBankName">Food Bank</option>
            </select>
            <input type="text" className="form-control form-control-sm" placeholder="Search..." style={{ width: 200 }}
              value={search} onChange={e => setSearch(e.target.value)} />
            <button type="submit" className="btn btn-sm btn-primary-sp px-3">Go</button>
          </form>
        </div>
        <div className="table-responsive">
          <table className="table table-hover mb-0">
            <thead>
              <tr><th>#</th><th>Food Bank</th><th>Item</th><th>Category</th><th>Unit</th><th>Quantity</th><th>Expiry</th></tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={7} className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></td></tr>
              ) : inventory.length === 0 ? (
                <tr><td colSpan={7} className="text-center py-4 text-muted">No inventory records found</td></tr>
              ) : inventory.map(i => (
                <tr key={i.intInventoryId}>
                  <td className="small text-muted">{i.intInventoryId}</td>
                  <td className="small">{i.strFoodBankName}</td>
                  <td className="small fw-semibold">{i.strItem}</td>
                  <td className="small">{i.strCategory}</td>
                  <td className="small">{i.strUnit}</td>
                  <td>
                    <span className={`badge-status ${i.intQuantity < 10 ? 'badge-rejected' : 'badge-approved'}`}>
                      {i.intQuantity}
                    </span>
                  </td>
                  <td className="small">{i.dtmExpirationDate}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        <div className="d-flex justify-content-between align-items-center p-3">
          <span className="text-muted small">Showing {inventory.length} of {total} records</span>
          <div className="d-flex gap-2">
            <button className="pagination-btn" onClick={() => setPage(p => p - 1)} disabled={page <= 1}>
              <i className="bi bi-chevron-left"></i> Prev
            </button>
            <span className="small d-flex align-items-center">Page {page} of {totalPages || 1}</span>
            <button className="pagination-btn" onClick={() => setPage(p => p + 1)} disabled={page >= totalPages}>
              Next <i className="bi bi-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
