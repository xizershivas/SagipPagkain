import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { beneficiaryService } from '../../services/api';

export default function ManageBeneficiary() {
  const [beneficiaries, setBeneficiaries] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [msg, setMsg] = useState('');

  const load = () => {
    setLoading(true);
    beneficiaryService.getAll().then(r => setBeneficiaries(r.data)).finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const handleActivate = async (id) => {
    await beneficiaryService.activate(id);
    setMsg('Beneficiary activated'); load();
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Deactivate this beneficiary?')) return;
    await beneficiaryService.delete(id);
    setMsg('Beneficiary deactivated'); load();
  };

  const filtered = beneficiaries.filter(b =>
    b.strName?.toLowerCase().includes(search.toLowerCase()) ||
    b.strEmail?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <DashboardLayout title="Manage Beneficiaries">
      {msg && <div className="alert alert-info py-2 small mb-3">{msg}</div>}
      <div className="data-table-container">
        <div className="data-table-header">
          <h6><i className="bi bi-people-fill me-2" style={{ color: 'var(--primary)' }}></i>Beneficiaries</h6>
          <input type="text" className="form-control form-control-sm" style={{ width: 250 }}
            placeholder="Search name or email..." value={search} onChange={e => setSearch(e.target.value)} />
        </div>
        <div className="table-responsive">
          <table className="table table-hover mb-0">
            <thead>
              <tr><th>#</th><th>Name</th><th>Email</th><th>Contact</th><th>Address</th><th>Salary</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={8} className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></td></tr>
              ) : filtered.length === 0 ? (
                <tr><td colSpan={8} className="text-center py-4 text-muted">No beneficiaries found</td></tr>
              ) : filtered.map(b => (
                <tr key={b.intBeneficiaryId}>
                  <td className="small text-muted">{b.intBeneficiaryId}</td>
                  <td className="small fw-semibold">{b.strName}</td>
                  <td className="small">{b.strEmail || '—'}</td>
                  <td className="small">{b.strContact || '—'}</td>
                  <td className="small">{b.strAddress || '—'}</td>
                  <td className="small">₱{(b.dblSalary || 0).toLocaleString()}</td>
                  <td>
                    <span className={`badge-status ${b.ysnActive ? 'badge-approved' : 'badge-rejected'}`}>
                      {b.ysnActive ? 'Active' : 'Inactive'}
                    </span>
                  </td>
                  <td>
                    <div className="d-flex gap-1">
                      {!b.ysnActive && (
                        <button className="btn btn-sm btn-outline-success" onClick={() => handleActivate(b.intBeneficiaryId)} title="Activate">
                          <i className="bi bi-check-circle"></i>
                        </button>
                      )}
                      <button className="btn btn-sm btn-outline-danger" onClick={() => handleDelete(b.intBeneficiaryId)} title="Deactivate">
                        <i className="bi bi-x-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        <div className="p-3 text-muted small">Total: {filtered.length} beneficiaries</div>
      </div>
    </DashboardLayout>
  );
}
