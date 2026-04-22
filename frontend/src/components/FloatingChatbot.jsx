import { useState, useRef, useEffect } from 'react';
import axios from 'axios';

const API_BASE = 'http://localhost:5198/api';

const SUGGESTED = [
  'What is Sagip Pagkain?',
  'How do I donate food?',
  'How can I get food assistance?',
  'Where are the food banks?',
  'How do I register?',
];

export default function FloatingChatbot() {
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState([
    {
      role: 'assistant',
      content: 'Hi! 👋 I\'m the Sagip Pagkain assistant. Ask me anything about our food banking platform — how to donate, how to get help, where we are, and more!',
    },
  ]);
  const [input, setInput] = useState('');
  const [loading, setLoading] = useState(false);
  const [showSuggested, setShowSuggested] = useState(true);
  const bottomRef = useRef(null);
  const inputRef = useRef(null);

  useEffect(() => {
    if (open) {
      bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
      inputRef.current?.focus();
    }
  }, [open, messages]);

  const send = async (text) => {
    const msg = text || input.trim();
    if (!msg || loading) return;

    setInput('');
    setShowSuggested(false);
    setMessages(prev => [...prev, { role: 'user', content: msg }]);
    setLoading(true);

    const history = messages.map(m => ({ role: m.role, content: m.content }));

    try {
      const res = await axios.post(`${API_BASE}/chat`, { message: msg, history });
      setMessages(prev => [...prev, { role: 'assistant', content: res.data.reply }]);
    } catch {
      setMessages(prev => [...prev, {
        role: 'assistant',
        content: 'Sorry, I\'m having trouble right now. Please try again in a moment.',
      }]);
    } finally {
      setLoading(false);
    }
  };

  const handleKey = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
  };

  const clearChat = () => {
    setMessages([{
      role: 'assistant',
      content: 'Hi! 👋 I\'m the Sagip Pagkain assistant. Ask me anything about our food banking platform!',
    }]);
    setShowSuggested(true);
  };

  return (
    <>
      {/* Floating Button */}
      {!open && (
        <button
          onClick={() => setOpen(true)}
          style={{
            position: 'fixed', bottom: 28, right: 28, zIndex: 9999,
            width: 60, height: 60, borderRadius: '50%',
            background: 'var(--primary)', border: 'none',
            boxShadow: '0 4px 20px rgba(29,104,100,0.5)',
            cursor: 'pointer', display: 'flex', alignItems: 'center',
            justifyContent: 'center', transition: 'transform 0.2s',
          }}
          onMouseEnter={e => e.currentTarget.style.transform = 'scale(1.1)'}
          onMouseLeave={e => e.currentTarget.style.transform = 'scale(1)'}
          title="Chat with us"
        >
          <i className="bi bi-chat-dots-fill text-white" style={{ fontSize: '1.5rem' }}></i>
          <span style={{
            position: 'absolute', top: -4, right: -4,
            width: 18, height: 18, borderRadius: '50%',
            background: '#F7B32B', fontSize: '0.6rem',
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontWeight: 700, color: '#000', border: '2px solid #fff',
          }}>AI</span>
        </button>
      )}

      {/* Chat Window */}
      {open && (
        <div style={{
          position: 'fixed', bottom: 24, right: 24, zIndex: 9999,
          width: 370, maxWidth: 'calc(100vw - 32px)',
          borderRadius: 20, overflow: 'hidden',
          boxShadow: '0 10px 60px rgba(0,0,0,0.25)',
          display: 'flex', flexDirection: 'column',
          background: '#fff', maxHeight: '80vh',
        }}>
          {/* Header */}
          <div style={{
            background: 'linear-gradient(135deg, var(--primary), #145450)',
            padding: '14px 16px',
            display: 'flex', alignItems: 'center', gap: 10,
          }}>
            <div style={{
              width: 38, height: 38, borderRadius: '50%',
              background: 'rgba(247,179,43,0.2)',
              display: 'flex', alignItems: 'center', justifyContent: 'center',
              flexShrink: 0,
            }}>
              <i className="bi bi-robot" style={{ color: '#F7B32B', fontSize: '1.2rem' }}></i>
            </div>
            <div style={{ flex: 1 }}>
              <div style={{ color: '#fff', fontWeight: 700, fontSize: '0.9rem' }}>Sagip Pagkain AI</div>
              <div style={{ color: 'rgba(255,255,255,0.65)', fontSize: '0.72rem' }}>
                <span style={{
                  display: 'inline-block', width: 7, height: 7,
                  background: '#4ade80', borderRadius: '50%', marginRight: 4,
                }}></span>
                Online · Powered by Claude AI
              </div>
            </div>
            <div style={{ display: 'flex', gap: 6 }}>
              <button onClick={clearChat} title="Clear chat"
                style={{ background: 'rgba(255,255,255,0.15)', border: 'none', borderRadius: 8, padding: '4px 8px', cursor: 'pointer', color: '#fff' }}>
                <i className="bi bi-arrow-counterclockwise" style={{ fontSize: '0.85rem' }}></i>
              </button>
              <button onClick={() => setOpen(false)} title="Close"
                style={{ background: 'rgba(255,255,255,0.15)', border: 'none', borderRadius: 8, padding: '4px 8px', cursor: 'pointer', color: '#fff' }}>
                <i className="bi bi-x-lg" style={{ fontSize: '0.85rem' }}></i>
              </button>
            </div>
          </div>

          {/* Messages */}
          <div style={{ flex: 1, overflowY: 'auto', padding: '14px 14px 8px', minHeight: 200, maxHeight: 420, background: '#f8fafa' }}>
            {messages.map((m, i) => (
              <div key={i} style={{
                display: 'flex',
                justifyContent: m.role === 'user' ? 'flex-end' : 'flex-start',
                marginBottom: 10,
                alignItems: 'flex-end', gap: 7,
              }}>
                {m.role === 'assistant' && (
                  <div style={{
                    width: 28, height: 28, borderRadius: '50%',
                    background: 'var(--primary)', flexShrink: 0,
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                  }}>
                    <i className="bi bi-robot text-white" style={{ fontSize: '0.75rem' }}></i>
                  </div>
                )}
                <div style={{
                  maxWidth: '80%',
                  padding: '9px 13px',
                  borderRadius: m.role === 'user' ? '18px 18px 4px 18px' : '18px 18px 18px 4px',
                  background: m.role === 'user' ? 'var(--primary)' : '#fff',
                  color: m.role === 'user' ? '#fff' : '#222',
                  fontSize: '0.85rem',
                  lineHeight: 1.5,
                  boxShadow: '0 1px 4px rgba(0,0,0,0.08)',
                  whiteSpace: 'pre-wrap',
                  wordBreak: 'break-word',
                }}>
                  {m.content}
                </div>
              </div>
            ))}

            {loading && (
              <div style={{ display: 'flex', alignItems: 'flex-end', gap: 7, marginBottom: 10 }}>
                <div style={{
                  width: 28, height: 28, borderRadius: '50%', background: 'var(--primary)',
                  flexShrink: 0, display: 'flex', alignItems: 'center', justifyContent: 'center',
                }}>
                  <i className="bi bi-robot text-white" style={{ fontSize: '0.75rem' }}></i>
                </div>
                <div style={{
                  padding: '10px 14px', background: '#fff',
                  borderRadius: '18px 18px 18px 4px', boxShadow: '0 1px 4px rgba(0,0,0,0.08)',
                }}>
                  <span style={{ display: 'flex', gap: 4, alignItems: 'center' }}>
                    {[0, 1, 2].map(d => (
                      <span key={d} style={{
                        width: 7, height: 7, borderRadius: '50%',
                        background: 'var(--primary)', opacity: 0.6,
                        animation: `bounce 1s ease-in-out ${d * 0.15}s infinite`,
                      }}></span>
                    ))}
                  </span>
                </div>
              </div>
            )}

            {/* Suggested questions */}
            {showSuggested && messages.length === 1 && (
              <div style={{ marginTop: 10 }}>
                <div style={{ fontSize: '0.72rem', color: '#999', marginBottom: 8, textAlign: 'center' }}>Quick questions</div>
                <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6 }}>
                  {SUGGESTED.map((q, i) => (
                    <button key={i} onClick={() => send(q)} style={{
                      background: '#fff', border: '1px solid #d0e8e6', borderRadius: 20,
                      padding: '5px 11px', fontSize: '0.76rem', cursor: 'pointer',
                      color: 'var(--primary)', fontWeight: 500, transition: '0.15s',
                    }}
                      onMouseEnter={e => { e.currentTarget.style.background = 'var(--primary)'; e.currentTarget.style.color = '#fff'; }}
                      onMouseLeave={e => { e.currentTarget.style.background = '#fff'; e.currentTarget.style.color = 'var(--primary)'; }}>
                      {q}
                    </button>
                  ))}
                </div>
              </div>
            )}

            <div ref={bottomRef} />
          </div>

          {/* Input */}
          <div style={{ padding: '10px 14px', borderTop: '1px solid #eee', background: '#fff' }}>
            <div style={{ display: 'flex', gap: 8, alignItems: 'flex-end' }}>
              <textarea
                ref={inputRef}
                rows={1}
                value={input}
                onChange={e => setInput(e.target.value)}
                onKeyDown={handleKey}
                placeholder="Ask anything about Sagip Pagkain..."
                disabled={loading}
                style={{
                  flex: 1, resize: 'none', border: '1.5px solid #e0eded',
                  borderRadius: 12, padding: '9px 12px', fontSize: '0.85rem',
                  outline: 'none', fontFamily: 'inherit', lineHeight: 1.4,
                  maxHeight: 100, overflowY: 'auto', background: '#f8fafa',
                  transition: 'border-color 0.15s',
                }}
                onFocus={e => e.target.style.borderColor = 'var(--primary)'}
                onBlur={e => e.target.style.borderColor = '#e0eded'}
              />
              <button
                onClick={() => send()}
                disabled={!input.trim() || loading}
                style={{
                  width: 38, height: 38, borderRadius: '50%', border: 'none',
                  background: input.trim() && !loading ? 'var(--primary)' : '#e0eded',
                  color: input.trim() && !loading ? '#fff' : '#aaa',
                  cursor: input.trim() && !loading ? 'pointer' : 'not-allowed',
                  display: 'flex', alignItems: 'center', justifyContent: 'center',
                  flexShrink: 0, transition: '0.2s',
                }}>
                <i className="bi bi-send-fill" style={{ fontSize: '0.9rem' }}></i>
              </button>
            </div>
            <div style={{ textAlign: 'center', fontSize: '0.68rem', color: '#bbb', marginTop: 6 }}>
              Powered by Claude AI · Press Enter to send
            </div>
          </div>
        </div>
      )}

      <style>{`
        @keyframes bounce {
          0%, 100% { transform: translateY(0); }
          50% { transform: translateY(-4px); }
        }
      `}</style>
    </>
  );
}
