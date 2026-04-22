import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { volunteerService } from '../../services/api';

const emptyForm = {
  strFirstName: '', strLastName: '', strGender: '', dtmDateOfBirth: '',
  strStreet: '', strAddress: '', strCity: '', strRegion: '', strZipCode: '',
  strCountry: 'Philippines', strContact: '', strEmail: ''
};

export default function VolunteerManagement() {
  const [volunteers, setVolunteers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editing, setEditing] = useState(null);
  const [form, setForm] = useState(emptyForm);
  const [signature, setSignature] = useState(null);
  const [saving, setSaving] = useState(false);
  const [msg, setMsg] = useState('');
  const [search, setSearch] = useState('');

  const load = () => {
    setLoading(true);
    volunteerService.getAll().then(r => setVolunteers(r.data)).finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const openAdd = () => { setEditing(null); setForm(emptyForm); setSignature(null); setShowModal(true); };

  const openEdit = async (id) => {
    const r = await volunteerService.getById(id);
    const v = r.data;
    setEditing(id);
    setForm({
      strFirstName: v.strFirstName, strLastName: v.strLastName, strGender: v.strGender || '',
      dtmDateOfBirth: v.dtmDateOfBirth?.slice(0, 10) || '', strStreet: v.strStreet || '',
      strAddress: v.strAddress || '', strCity: v.strCity || '', strRegion: v.strRegion || '',
      strZipCode: v.strZipCode || '', strCountry: v.strCountry || 'Philippines',
      strContact: v.strContact || '', strEmail: v.strEmail || ''
    });
    setSignature(null); setShowModal(true);
  };

  const handleSave = async () => {
    setSaving(true); setMsg('');
    try {
      const fd = new FormData();
      Object.entries(form).forEach(([k, v]) => { if (v) fd.append(k, v); });
      if (signature) fd.append('signature', signature);
      if (editing) { await volunteerService.update(editing, fd); }
      else { await volunteerService.create(fd); }
      setMsg(editing ? 'Volunteer updated' : 'Volunteer added');
      setShowModal(false); load();
    } catch { setMsg('Operation failed'); }
    finally { setSaving(false); }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Delete this volunteer?')) return;
    await volunteerService.delete(id);
    load();
  };

  const filtered = volunteers.filter(v =>
    `${v.strFirstName} ${v.strLastName}`.toLowerCase().includes(search.toLowerCase()) ||
    v.strEmail?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <DashboardLayout title="Volunteer Management">
      {msg && <div className="alert alert-info py-2 small mb-3">{msg}</div>}
      <div className="data-table-container">
        <div className="data-table-header">
          <h6><i className="bi bi-person-badge me-2" style={{ color: 'var(--primary)' }}></i>Volunteers</h6>
          <div className="d-flex gap-2">
            <input type="text" className="form-control form-control-sm" placeholder="Search..." style={{ width: 200 }}
              value={search} onChange={e => setSearch(e.target.value)} />
            <button className="btn btn-sm btn-primary-sp" onClick={openAdd}>
              <i className="bi bi-plus-lg me-1"></i>Add
            </button>
          </div>
        </div>
        <div className="table-responsive">
          <table className="table table-hover mb-0">
            <thead>
              <tr><th>#</th><th>Name</th><th>Gender</th><th>Contact</th><th>Email</th><th>City</th><th>Actions</th></tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={7} className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></td></tr>
              ) : filtered.length === 0 ? (
                <tr><td colSpan={7} className="text-center py-4 text-muted">No volunteers found</td></tr>
              ) : filtered.map(v => (
                <tr key={v.intVolunteerId}>
                  <td className="small text-muted">{v.intVolunteerId}</td>
                  <td className="small fw-semibold">{v.strFirstName} {v.strLastName}</td>
                  <td className="small">{v.strGender || '—'}</td>
                  <td className="small">{v.strContact || '—'}</td>
                  <td className="small">{v.strEmail || '—'}</td>
                  <td className="small">{v.strCity || '—'}</td>
                  <td>
                    <div className="d-flex gap-1">
                      <button className="btn btn-sm btn-outline-secondary" onClick={() => openEdit(v.intVolunteerId)}>
                        <i className="bi bi-pencil"></i>
                      </button>
                      <button className="btn btn-sm btn-outline-danger" onClick={() => handleDelete(v.intVolunteerId)}>
                        <i className="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {showModal && (
        <div className="modal show d-block" style={{ background: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog modal-lg">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">{editing ? 'Edit Volunteer' : 'Add Volunteer'}</h5>
                <button className="btn-close" onClick={() => setShowModal(false)}></button>
              </div>
              <div className="modal-body">
                <div className="row g-3">
                  <div className="col-md-4">
                    <label className="form-label small fw-semibold">First Name *</label>
                    <input className="form-control" value={form.strFirstName} onChange={e => setForm({ ...form, strFirstName: e.target.value })} required />
                  </div>
                  <div className="col-md-4">
                    <label className="form-label small fw-semibold">Last Name *</label>
                    <input className="form-control" value={form.strLastName} onChange={e => setForm({ ...form, strLastName: e.target.value })} required />
                  </div>
                  <div className="col-md-2">
                    <label className="form-label small fw-semibold">Gender</label>
                    <select className="form-select" value={form.strGender} onChange={e => setForm({ ...form, strGender: e.target.value })}>
                      <option value="">Select</option>
                      <option>Male</option><option>Female</option><option>Other</option>
                    </select>
                  </div>
                  <div className="col-md-2">
                    <label className="form-label small fw-semibold">Date of Birth</label>
                    <input type="date" className="form-control" value={form.dtmDateOfBirth} onChange={e => setForm({ ...form, dtmDateOfBirth: e.target.value })} />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label small fw-semibold">Contact</label>
                    <input className="form-control" value={form.strContact} onChange={e => setForm({ ...form, strContact: e.target.value })} />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label small fw-semibold">Email</label>
                    <input type="email" className="form-control" value={form.strEmail} onChange={e => setForm({ ...form, strEmail: e.target.value })} />
                  </div>
                  <div className="col-12">
                    <label className="form-label small fw-semibold">Street</label>
                    <input className="form-control" value={form.strStreet} onChange={e => setForm({ ...form, strStreet: e.target.value })} />
                  </div>
                  <div className="col-md-4">
                    <label className="form-label small fw-semibold">City</label>
                    <input className="form-control" value={form.strCity} onChange={e => setForm({ ...form, strCity: e.target.value })} />
                  </div>
                  <div className="col-md-4">
                    <label className="form-label small fw-semibold">Region</label>
                    <input className="form-control" value={form.strRegion} onChange={e => setForm({ ...form, strRegion: e.target.value })} />
                  </div>
                  <div className="col-md-2">
                    <label className="form-label small fw-semibold">ZIP</label>
                    <input className="form-control" value={form.strZipCode} onChange={e => setForm({ ...form, strZipCode: e.target.value })} />
                  </div>
                  <div className="col-md-2">
                    <label className="form-label small fw-semibold">Country</label>
                    <input className="form-control" value={form.strCountry} onChange={e => setForm({ ...form, strCountry: e.target.value })} />
                  </div>
                  <div className="col-12">
                    <label className="form-label small fw-semibold">Signature (JPG/PNG, max 3MB)</label>
                    <input type="file" className="form-control" accept=".jpg,.jpeg,.png"
                      onChange={e => setSignature(e.target.files[0])} />
                  </div>
                </div>
              </div>
              <div className="modal-footer">
                <button className="btn btn-secondary btn-sm" onClick={() => setShowModal(false)}>Cancel</button>
                <button className="btn btn-sm btn-primary-sp" onClick={handleSave} disabled={saving}>
                  {saving ? <span className="spinner-border spinner-border-sm me-1"></span> : null} Save
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </DashboardLayout>
  );
}
