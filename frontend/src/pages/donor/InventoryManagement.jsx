import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { inventoryService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function DonorInventory() {
  const { user } = useAuth();
  const [inventory, setInventory] = useState([]);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [filter, setFilter] = useState('strCategory');
  const [page, setPage] = useState(1);
  const limit = 10;

  const load = () => {
    setLoading(true);
    inventoryService.getAll({ userId: user.userId, search, filter, page, limit })
      .then(r => { setInventory(r.data.data); setTotal(r.data.totalRecords); })
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, [page]);

  const totalPages = Math.ceil(total / limit);

  return (
    <DashboardLayout title="Inventory at My Food Bank">
      <div className="data-table-container">
        <div className="data-table-header">
          <h6><i className="bi bi-box-seam me-2" style={{ color: 'var(--primary)' }}></i>Inventory Status</h6>
          <form className="d-flex gap-2" onSubmit={e => { e.preventDefault(); setPage(1); load(); }}>
            <select className="form-select form-select-sm" style={{ width: 150 }} value={filter} onChange={e => setFilter(e.target.value)}>
              <option value="strCategory">Category</option>
              <option value="strItem">Item</option>
              <option value="strFoodBankName">Food Bank</option>
            </select>
            <input type="text" className="form-control form-control-sm" placeholder="Search..." style={{ width: 200 }}
              value={search} onChange={e => setSearch(e.target.value)} />
            <button type="submit" className="btn btn-sm btn-primary-sp">Go</button>
          </form>
        </div>
        <div className="table-responsive">
          <table className="table table-hover mb-0">
            <thead>
              <tr><th>Food Bank</th><th>Item</th><th>Category</th><th>Quantity</th><th>Unit</th><th>Expiry</th></tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={6} className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></td></tr>
              ) : inventory.length === 0 ? (
                <tr><td colSpan={6} className="text-center py-4 text-muted">No inventory found</td></tr>
              ) : inventory.map(i => (
                <tr key={i.intInventoryId}>
                  <td className="small">{i.strFoodBankName}</td>
                  <td className="small fw-semibold">{i.strItem}</td>
                  <td className="small">{i.strCategory}</td>
                  <td><span className={`badge-status ${i.intQuantity < 10 ? 'badge-rejected' : 'badge-approved'}`}>{i.intQuantity}</span></td>
                  <td className="small">{i.strUnit}</td>
                  <td className="small">{i.dtmExpirationDate}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        <div className="d-flex justify-content-between align-items-center p-3">
          <span className="text-muted small">{inventory.length} of {total}</span>
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
      </div>
    </DashboardLayout>
  );
}
