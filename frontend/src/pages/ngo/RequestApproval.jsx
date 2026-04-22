import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { beneficiaryService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function NgoRequests() {
  const { user } = useAuth();
  const [requests, setRequests] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selected, setSelected] = useState(null);
  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState('all');
  const [msg, setMsg] = useState('');

  const load = () => {
    setLoading(true);
    beneficiaryService.getRequests({ foodBankUserId: user.userId })
      .then(r => setRequests(r.data)).finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, [user.userId]);

  const handleStatus = async (id, status) => {
    await beneficiaryService.updateRequestStatus(id, status);
    setMsg(`Request ${status}`);
    load();
    setSelected(null);
  };

  const filtered = requests.filter(r => {
    const matchSearch = r.strBeneficiaryName?.toLowerCase().includes(search.toLowerCase()) ||
      r.strRequestNo?.toLowerCase().includes(search.toLowerCase());
    const matchStatus = statusFilter === 'all' || r.strStatus?.toLowerCase() === statusFilter;
    return matchSearch && matchStatus;
  });

  const urgencyColor = { High: 'danger', Medium: 'warning', Low: 'success' };

  return (
    <DashboardLayout title="Request Approval">
      {msg && <div className="alert alert-info py-2 small mb-3">{msg}</div>}
      <div className="data-table-container">
        <div className="data-table-header">
          <h6><i className="bi bi-clipboard-check me-2" style={{ color: 'var(--primary)' }}></i>Assistance Requests</h6>
          <div className="d-flex gap-2">
            <select className="form-select form-select-sm" style={{ width: 120 }} value={statusFilter}
              onChange={e => setStatusFilter(e.target.value)}>
              <option value="all">All Status</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="completed">Completed</option>
              <option value="rejected">Rejected</option>
            </select>
            <input type="text" className="form-control form-control-sm" style={{ width: 200 }}
              placeholder="Search beneficiary..." value={search} onChange={e => setSearch(e.target.value)} />
          </div>
        </div>
        <div className="table-responsive">
          <table className="table table-hover mb-0">
            <thead>
              <tr><th>Request No</th><th>Beneficiary</th><th>Type</th><th>Urgency</th><th>Pickup</th><th>Items</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={8} className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></td></tr>
              ) : filtered.length === 0 ? (
                <tr><td colSpan={8} className="text-center py-4 text-muted">No requests found</td></tr>
              ) : filtered.map(r => (
                <tr key={r.intBeneficiaryRequestId}>
                  <td className="small fw-semibold" style={{ color: 'var(--primary)' }}>{r.strRequestNo}</td>
                  <td className="small">{r.strBeneficiaryName}</td>
                  <td className="small">{r.strRequestType}</td>
                  <td>
                    <span className={`badge bg-${urgencyColor[r.strUrgencyLevel] || 'secondary'} text-white`} style={{ fontSize: '0.75rem' }}>
                      {r.strUrgencyLevel}
                    </span>
                  </td>
                  <td className="small">{new Date(r.dtmPickupDate).toLocaleDateString()}</td>
                  <td className="small">{r.itemNames?.join(', ') || '—'}</td>
                  <td><span className={`badge-status badge-${r.strStatus?.toLowerCase()}`}>{r.strStatus}</span></td>
                  <td>
                    <button className="btn btn-sm btn-outline-secondary" onClick={() => setSelected(r)}>
                      <i className="bi bi-eye"></i>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {selected && (
        <div className="modal show d-block" style={{ background: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog modal-lg">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">Request {selected.strRequestNo}</h5>
                <button className="btn-close" onClick={() => setSelected(null)}></button>
              </div>
              <div className="modal-body">
                <div className="row g-3 small">
                  <div className="col-md-6">
                    <div className="p-3 bg-light rounded">
                      <div className="text-muted mb-1">Beneficiary</div>
                      <div className="fw-semibold">{selected.strBeneficiaryName}</div>
                    </div>
                  </div>
                  <div className="col-md-3">
                    <div className="p-3 bg-light rounded">
                      <div className="text-muted mb-1">Request Type</div>
                      <div className="fw-semibold">{selected.strRequestType}</div>
                    </div>
                  </div>
                  <div className="col-md-3">
                    <div className="p-3 bg-light rounded">
                      <div className="text-muted mb-1">Urgency</div>
                      <div className={`fw-semibold text-${urgencyColor[selected.strUrgencyLevel]}`}>{selected.strUrgencyLevel}</div>
                    </div>
                  </div>
                  <div className="col-md-6">
                    <div className="p-3 bg-light rounded">
                      <div className="text-muted mb-1">Items Requested</div>
                      <div className="fw-semibold">{selected.itemNames?.join(', ') || '—'}</div>
                    </div>
                  </div>
                  <div className="col-md-3">
                    <div className="p-3 bg-light rounded">
                      <div className="text-muted mb-1">Pickup Date</div>
                      <div className="fw-semibold">{new Date(selected.dtmPickupDate).toLocaleDateString()}</div>
                    </div>
                  </div>
                  <div className="col-md-3">
                    <div className="p-3 bg-light rounded">
                      <div className="text-muted mb-1">Purpose</div>
                      <div className="fw-semibold">{selected.strPurpose}</div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="modal-footer">
                {selected.strStatus === 'Pending' && (
                  <>
                    <button className="btn btn-success btn-sm" onClick={() => handleStatus(selected.intBeneficiaryRequestId, 'Approved')}>
                      <i className="bi bi-check-circle me-1"></i>Approve
                    </button>
                    <button className="btn btn-danger btn-sm" onClick={() => handleStatus(selected.intBeneficiaryRequestId, 'Rejected')}>
                      <i className="bi bi-x-circle me-1"></i>Reject
                    </button>
                  </>
                )}
                {selected.strStatus === 'Approved' && (
                  <button className="btn btn-primary btn-sm" onClick={() => handleStatus(selected.intBeneficiaryRequestId, 'Completed')}>
                    <i className="bi bi-check2-all me-1"></i>Mark Completed
                  </button>
                )}
                <button className="btn btn-secondary btn-sm" onClick={() => setSelected(null)}>Close</button>
              </div>
            </div>
          </div>
        </div>
      )}
    </DashboardLayout>
  );
}
