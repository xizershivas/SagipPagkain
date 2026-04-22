import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { lookupService } from '../../services/api';

export default function FoodBankCenter() {
  const [foodBanks, setFoodBanks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  useEffect(() => {
    lookupService.getFoodBanks().then(r => setFoodBanks(r.data)).finally(() => setLoading(false));
  }, []);

  const filtered = foodBanks.filter(fb =>
    fb.strFoodBankName?.toLowerCase().includes(search.toLowerCase()) ||
    fb.strAddress?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <DashboardLayout title="Food Bank Centers">
      <div className="mb-3">
        <input type="text" className="form-control" placeholder="Search food banks..."
          value={search} onChange={e => setSearch(e.target.value)} />
      </div>

      {loading ? (
        <div className="text-center py-5"><div className="spinner-border" style={{ color: 'var(--primary)' }}></div></div>
      ) : (
        <div className="row g-3">
          {filtered.length === 0 ? (
            <div className="col-12 text-center py-5 text-muted">No food banks found</div>
          ) : filtered.map(fb => (
            <div key={fb.intFoodBankDetailId} className="col-md-4">
              <div className="card border-0 shadow-sm h-100" style={{ borderRadius: 12 }}>
                <div className="card-body p-4">
                  <div className="d-flex align-items-start gap-3 mb-3">
                    <div className="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                      style={{ width: 48, height: 48, background: 'rgba(29,104,100,0.1)' }}>
                      <i className="bi bi-building fs-5" style={{ color: 'var(--primary)' }}></i>
                    </div>
                    <div>
                      <h6 className="fw-bold mb-1" style={{ color: 'var(--primary)' }}>{fb.strFoodBankName}</h6>
                      {fb.strAddress && (
                        <div className="text-muted small">
                          <i className="bi bi-geo-alt me-1"></i>{fb.strAddress}
                        </div>
                      )}
                    </div>
                  </div>

                  {(fb.dblLatitude && fb.dblLongitude) && (
                    <div className="mt-2">
                      <a
                        href={`https://www.google.com/maps/search/?api=1&query=${fb.dblLatitude},${fb.dblLongitude}`}
                        target="_blank" rel="noopener noreferrer"
                        className="btn btn-sm w-100 btn-outline-secondary">
                        <i className="bi bi-map me-2"></i>View on Map
                      </a>
                    </div>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </DashboardLayout>
  );
}
