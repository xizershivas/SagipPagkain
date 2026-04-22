import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

export default function Landing() {
  const navigate = useNavigate();
  const [activeSection, setActiveSection] = useState('about');

  const scrollTo = (id) => {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
    setActiveSection(id);
  };

  return (
    <div style={{ fontFamily: 'Segoe UI, sans-serif' }}>
      {/* Navbar */}
      <nav className="navbar navbar-expand-lg sticky-top" style={{ background: 'var(--primary)', padding: '0.6rem 1.5rem' }}>
        <div className="container-fluid">
          <Link className="navbar-brand d-flex align-items-center gap-2" to="/">
            <img src="/img/sagiplogo.png" alt="Logo" width="36" />
            <span style={{ color: 'var(--accent)', fontWeight: 700, fontSize: '1.1rem' }}>Sagip Pagkain</span>
          </Link>
          <button className="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className="collapse navbar-collapse" id="navMenu">
            <ul className="navbar-nav ms-auto gap-1">
              {['about', 'services', 'stats', 'blog'].map(s => (
                <li key={s} className="nav-item">
                  <button className="nav-link btn btn-link text-white-50 text-capitalize" onClick={() => scrollTo(s)}>{s}</button>
                </li>
              ))}
            </ul>
            <div className="d-flex gap-2 ms-2">
              <Link to="/login" className="btn btn-outline-light btn-sm">Login</Link>
              <Link to="/signup" className="btn btn-sm" style={{ background: 'var(--accent)', color: '#000', fontWeight: 600 }}>Sign Up</Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero */}
      <section style={{
        minHeight: '85vh', display: 'flex', alignItems: 'center',
        background: `linear-gradient(rgba(22,83,85,0.85), rgba(22,83,85,0.9)), url('/img/vegetables-img1.jpg') center/cover`,
        color: '#fff'
      }}>
        <div className="container">
          <div className="row align-items-center g-5">
            <div className="col-lg-7">
              <span className="badge mb-3" style={{ background: 'var(--accent)', color: '#000', fontSize: '0.8rem', padding: '0.4rem 0.9rem' }}>
                Fighting Food Insecurity in the Philippines
              </span>
              <h1 className="display-4 fw-bold mb-3">Connecting Food Donors with Those in Need</h1>
              <p className="lead mb-4 opacity-75">
                Sagip Pagkain bridges the gap between food surplus and food poverty. Join our network of donors,
                food banks, and beneficiaries to reduce waste and nourish communities.
              </p>
              <div className="d-flex flex-wrap gap-3">
                <Link to="/signup" className="btn btn-lg px-4 fw-semibold" style={{ background: 'var(--accent)', color: '#000' }}>
                  <i className="bi bi-heart-fill me-2"></i>Start Donating
                </Link>
                <button className="btn btn-lg btn-outline-light px-4" onClick={() => scrollTo('about')}>
                  <i className="bi bi-info-circle me-2"></i>Learn More
                </button>
              </div>
            </div>
            <div className="col-lg-5 text-center">
              <img src="/img/sagip-pagkain-logo.jpeg" alt="Sagip Pagkain" className="img-fluid rounded-4 shadow-lg" style={{ maxWidth: 340, border: '4px solid var(--accent)' }} />
            </div>
          </div>
        </div>
      </section>

      {/* Stats Banner */}
      <section id="stats" style={{ background: 'var(--accent)', padding: '2.5rem 0' }}>
        <div className="container">
          <div className="row g-4 text-center">
            {[
              { num: '5,000+', label: 'Meals Distributed', icon: 'bi-basket2-fill' },
              { num: '150+', label: 'Active Donors', icon: 'bi-people-fill' },
              { num: '12', label: 'Food Bank Centers', icon: 'bi-building' },
              { num: '800+', label: 'Families Helped', icon: 'bi-house-heart-fill' },
            ].map((s, i) => (
              <div key={i} className="col-6 col-md-3">
                <i className={`bi ${s.icon} fs-2 mb-2`} style={{ color: 'var(--primary)' }}></i>
                <h3 className="fw-bold mb-0" style={{ color: 'var(--primary)', fontSize: '2rem' }}>{s.num}</h3>
                <p className="mb-0 fw-semibold" style={{ color: 'var(--primary)' }}>{s.label}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* About / Food Categories */}
      <section id="about" style={{ padding: '5rem 0', background: '#fff' }}>
        <div className="container">
          <div className="text-center mb-5">
            <h2 className="fw-bold" style={{ color: 'var(--primary)' }}>What We Accept</h2>
            <p className="text-muted">We collect all types of nutritious food items for redistribution</p>
          </div>
          <div className="row g-4 justify-content-center">
            {[
              { img: '/img/about/vegetable-1.png', label: 'Vegetables' },
              { img: '/img/about/fruit-1.png', label: 'Fruits' },
              { img: '/img/about/bread-1.png', label: 'Bread & Bakery' },
              { img: '/img/about/meat-1.png', label: 'Meat & Poultry' },
              { img: '/img/about/fish-1.png', label: 'Fish & Seafood' },
              { img: '/img/about/dairy-1.png', label: 'Dairy Products' },
            ].map((item, i) => (
              <div key={i} className="col-6 col-md-4 col-lg-2 text-center">
                <div className="p-3 rounded-3 h-100" style={{ background: '#f8f9fa', border: '2px solid transparent', transition: '0.2s' }}
                  onMouseEnter={e => e.currentTarget.style.borderColor = 'var(--primary)'}
                  onMouseLeave={e => e.currentTarget.style.borderColor = 'transparent'}>
                  <img src={item.img} alt={item.label} style={{ width: 70, height: 70, objectFit: 'contain' }} />
                  <p className="mt-2 mb-0 fw-semibold small" style={{ color: 'var(--primary)' }}>{item.label}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* How It Works */}
      <section style={{ padding: '5rem 0', background: '#f8f9fa' }}>
        <div className="container">
          <div className="text-center mb-5">
            <h2 className="fw-bold" style={{ color: 'var(--primary)' }}>How It Works</h2>
            <p className="text-muted">Simple steps to make a difference</p>
          </div>
          <div className="row g-4">
            {[
              { step: '01', icon: 'bi-person-plus', title: 'Register', desc: 'Create an account as a donor, food bank, or beneficiary.' },
              { step: '02', icon: 'bi-gift', title: 'Donate Food', desc: 'Donors submit food items with details to their nearest food bank.' },
              { step: '03', icon: 'bi-box-seam', title: 'Food Bank Manages', desc: 'Food banks organize received donations and manage inventory.' },
              { step: '04', icon: 'bi-house-heart', title: 'Beneficiaries Receive', desc: 'Families in need request and receive food assistance.' },
            ].map((item, i) => (
              <div key={i} className="col-md-3">
                <div className="text-center p-4">
                  <div className="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle"
                    style={{ width: 80, height: 80, background: 'var(--primary)' }}>
                    <i className={`bi ${item.icon} fs-2 text-white`}></i>
                  </div>
                  <span className="badge mb-2" style={{ background: 'var(--accent)', color: '#000' }}>Step {item.step}</span>
                  <h5 className="fw-bold" style={{ color: 'var(--primary)' }}>{item.title}</h5>
                  <p className="text-muted small">{item.desc}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Services */}
      <section id="services" style={{ padding: '5rem 0', background: '#fff' }}>
        <div className="container">
          <div className="text-center mb-5">
            <h2 className="fw-bold" style={{ color: 'var(--primary)' }}>Our Food Bank Centers</h2>
            <p className="text-muted">Strategically located to serve communities across Metro Manila</p>
          </div>
          <div className="row g-4">
            {[
              { img: '/img/bankFood/school.jpg', title: 'Schools', desc: 'Partnered with schools to provide nutritious meals to students.' },
              { img: '/img/bankFood/kitchen.jpg', title: 'Community Kitchens', desc: 'Distributing food through community kitchens serving families.' },
              { img: '/img/bankFood/shelter.jpg', title: 'Shelters', desc: 'Supporting shelters and evacuation centers during calamities.' },
              { img: '/img/bankFood/pantries.jpg', title: 'Food Pantries', desc: 'Local pantries that make food accessible to those in need.' },
            ].map((s, i) => (
              <div key={i} className="col-md-3">
                <div className="card border-0 shadow-sm h-100 overflow-hidden" style={{ borderRadius: 12 }}>
                  <img src={s.img} className="card-img-top" alt={s.title} style={{ height: 180, objectFit: 'cover' }} />
                  <div className="card-body">
                    <h6 className="fw-bold" style={{ color: 'var(--primary)' }}>{s.title}</h6>
                    <p className="text-muted small mb-0">{s.desc}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Blog */}
      <section id="blog" style={{ padding: '5rem 0', background: '#f8f9fa' }}>
        <div className="container">
          <div className="text-center mb-5">
            <h2 className="fw-bold" style={{ color: 'var(--primary)' }}>Community Stories</h2>
            <p className="text-muted">Real impact from real people</p>
          </div>
          <div className="row g-4">
            {[
              { img: '/img/blog/blog-1.jpg', title: 'Feeding 200 Families in QC', date: 'March 15, 2025', desc: 'Our biggest distribution event reached 200 families in Quezon City.' },
              { img: '/img/blog/blog-2.jpg', title: 'School Feeding Program', date: 'February 28, 2025', desc: 'Partnered with 5 schools to ensure students get proper nutrition.' },
              { img: '/img/blog/blog-3.jpg', title: 'Emergency Relief Operations', date: 'January 10, 2025', desc: 'Rapid response to flooding in Marikina provided 500 food packs.' },
            ].map((b, i) => (
              <div key={i} className="col-md-4">
                <div className="card border-0 shadow-sm h-100" style={{ borderRadius: 12 }}>
                  <img src={b.img} className="card-img-top" alt={b.title} style={{ height: 200, objectFit: 'cover', borderRadius: '12px 12px 0 0' }} />
                  <div className="card-body">
                    <p className="text-muted small mb-1"><i className="bi bi-calendar3 me-1"></i>{b.date}</p>
                    <h6 className="fw-bold" style={{ color: 'var(--primary)' }}>{b.title}</h6>
                    <p className="text-muted small">{b.desc}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section style={{
        padding: '4rem 0',
        background: `linear-gradient(rgba(22,83,85,0.9), rgba(22,83,85,0.95)), url('/img/cta-bg.jpg') center/cover`,
        color: '#fff', textAlign: 'center'
      }}>
        <div className="container">
          <h2 className="fw-bold mb-2">Ready to Make a Difference?</h2>
          <p className="mb-4 opacity-75">Join thousands of donors and volunteers making food accessible to all Filipinos.</p>
          <div className="d-flex justify-content-center gap-3 flex-wrap">
            <Link to="/signup" className="btn btn-lg px-5 fw-semibold" style={{ background: 'var(--accent)', color: '#000' }}>
              <i className="bi bi-heart-fill me-2"></i>Donate Now
            </Link>
            <Link to="/login" className="btn btn-lg btn-outline-light px-5">
              Sign In
            </Link>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer style={{ background: '#111', color: '#aaa', padding: '2rem 0' }}>
        <div className="container text-center">
          <img src="/img/sagiplogo.png" alt="Logo" width="40" className="mb-2 opacity-50" />
          <p className="mb-0 small">© 2025 Sagip Pagkain. All rights reserved. Bridging food surplus and food poverty.</p>
        </div>
      </footer>
    </div>
  );
}
