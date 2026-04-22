import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { beneficiaryService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function RequestStatus() {
  const { user } = useAuth();
  const [requests, setRequests] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selected, setSelected] = useState(null);

  const load = () => {
    setLoading(true);
    beneficiaryService.getRequests({ userId: user.userId })
      .then(r => setRequests(r.data)).finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, [user.userId]);

  const handleDelete = async (id) => {
    if (!window.confirm('Delete this request?')) return;
    await beneficiaryService.deleteRequest(id);
    load();
  };

  const statusIcon = { Pending: 'bi-clock text-warning', Approved: 'bi-check-circle text-success', Completed: 'bi-check2-all text-primary', Rejected: 'bi-x-circle text-danger' };

  return (
    <DashboardLayout title="My Requests">
      <div className="row g-3">
        <div className="col-md-5">
          <div className="form-card p-0 overflow-hidden">
            <div className="p-3 border-bottom">
              <h6 className="mb-0 fw-bold" style={{ color: 'var(--primary)' }}>Request History</h6>
            </div>
            {loading ? <div className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></div> : (
              requests.length === 0 ? (
                <p className="text-center text-muted py-4 small">No requests submitted yet</p>
              ) : requests.map(r => (
                <div key={r.intBeneficiaryRequestId}
                  className="p-3 border-bottom"
                  style={{
                    cursor: 'pointer',
                    background: selected?.intBeneficiaryRequestId === r.intBeneficiaryRequestId ? 'rgba(29,104,100,0.05)' : ''
                  }}
                  onClick={() => setSelected(r)}>
                  <div className="d-flex justify-content-between align-items-start">
                    <div>
                      <div className="fw-semibold small" style={{ color: 'var(--primary)' }}>{r.strRequestNo}</div>
                      <div className="text-muted small">{r.strRequestType} • {r.strUrgencyLevel}</div>
                      <div style={{ fontSize: '0.75rem' }} className="text-muted">
                        {new Date(r.dtmCreatedAt).toLocaleDateString()}
                      </div>
                    </div>
                    <div className="d-flex align-items-center gap-2">
                      <span className={`badge-status badge-${r.strStatus?.toLowerCase()}`}>{r.strStatus}</span>
                      {r.strStatus === 'Pending' && (
                        <button className="btn btn-sm btn-outline-danger p-1" style={{ lineHeight: 1 }}
                          onClick={e => { e.stopPropagation(); handleDelete(r.intBeneficiaryRequestId); }}>
                          <i className="bi bi-trash" style={{ fontSize: '0.75rem' }}></i>
                        </button>
                      )}
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
        <div className="col-md-7">
          {selected ? (
            <div className="form-card">
              <div className="d-flex align-items-center gap-2 mb-4">
                <i className={`bi ${statusIcon[selected.strStatus]} fs-4`}></i>
                <div>
                  <h6 className="mb-0 fw-bold" style={{ color: 'var(--primary)' }}>{selected.strRequestNo}</h6>
                  <div className="text-muted small">{selected.strStatus}</div>
                </div>
              </div>
              <div className="row g-2 small">
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Request Type</div>
                    <div className="fw-semibold">{selected.strRequestType}</div>
                  </div>
                </div>
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Urgency Level</div>
                    <div className={`fw-semibold ${selected.strUrgencyLevel === 'High' ? 'text-danger' : selected.strUrgencyLevel === 'Medium' ? 'text-warning' : 'text-success'}`}>
                      {selected.strUrgencyLevel}
                    </div>
                  </div>
                </div>
                <div className="col-12">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Food Bank</div>
                    <div className="fw-semibold">{selected.strFoodBankName}</div>
                  </div>
                </div>
                <div className="col-12">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted mb-1">Items Requested</div>
                    <div className="d-flex flex-wrap gap-1">
                      {selected.itemNames?.map((item, i) => (
                        <span key={i} className="badge" style={{ background: 'rgba(29,104,100,0.1)', color: 'var(--primary)' }}>{item}</span>
                      ))}
                    </div>
                  </div>
                </div>
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Pickup Date</div>
                    <div className="fw-semibold">{new Date(selected.dtmPickupDate).toLocaleDateString()}</div>
                  </div>
                </div>
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Submitted</div>
                    <div className="fw-semibold">{new Date(selected.dtmCreatedAt).toLocaleDateString()}</div>
                  </div>
                </div>
              </div>
            </div>
          ) : (
            <div className="form-card d-flex align-items-center justify-content-center" style={{ minHeight: 300 }}>
              <div className="text-center text-muted">
                <i className="bi bi-clipboard fs-1 d-block mb-3 opacity-25"></i>
                <p>Select a request to view details</p>
              </div>
            </div>
          )}
        </div>
      </div>
    </DashboardLayout>
  );
}
