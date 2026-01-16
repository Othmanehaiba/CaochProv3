// Simple helpers
function qs(sel, parent=document){ return parent.querySelector(sel); }
function qsa(sel, parent=document){ return [...parent.querySelectorAll(sel)]; }

function openModal(id){
  const bd = qs(`#${id}`);
  if(!bd) return;
  bd.style.display = "flex";
  bd.setAttribute("aria-hidden","false");
}
function closeModal(id){
  const bd = qs(`#${id}`);
  if(!bd) return;
  bd.style.display = "none";
  bd.setAttribute("aria-hidden","true");
}

// Close modal on backdrop click
document.addEventListener("click", (e) => {
  const bd = e.target.closest(".modal-backdrop");
  if(bd && e.target === bd){
    closeModal(bd.id);
  }
});

// Basic Regex validation
const RX = {
  email: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
  phone: /^[0-9+\s().-]{8,}$/,
  password: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$/
};

function setFieldError(field, msg){
  const box = field.closest(".field");
  let err = qs(".error", box);
  if(!err){
    err = document.createElement("div");
    err.className = "error";
    box.appendChild(err);
  }
  err.textContent = msg;
  field.setAttribute("aria-invalid","true");
}
function clearFieldError(field){
  const box = field.closest(".field");
  const err = qs(".error", box);
  if(err) err.remove();
  field.removeAttribute("aria-invalid");
}

function validateForm(form){
  let ok = true;

  qsa("[data-validate]", form).forEach((field) => {
    const rule = field.dataset.validate;
    const v = field.value.trim();

    clearFieldError(field);

    if(field.required && !v){
      setFieldError(field, "Champ obligatoire.");
      ok = false; return;
    }
    if(!v) return;

    if(rule === "email" && !RX.email.test(v)){ setFieldError(field,"Email invalide."); ok=false; }
    if(rule === "phone" && !RX.phone.test(v)){ setFieldError(field,"Téléphone invalide."); ok=false; }
    if(rule === "password" && !RX.password.test(v)){
      setFieldError(field,"Mot de passe: 8+ caractères, au moins 1 lettre et 1 chiffre.");
      ok=false;
    }
    if(rule === "match"){
      const other = qs(field.dataset.match);
      if(other && other.value !== field.value){
        setFieldError(field,"Les deux champs ne correspondent pas.");
        ok=false;
      }
    }
  });

  return ok;
}

document.addEventListener("submit", (e) => {
  const form = e.target.closest("form[data-js='validate']");
  if(!form) return;
  if(!validateForm(form)){
    e.preventDefault();
  }
});

document.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-confirm]");
  if(!btn) return;

  const title = btn.dataset.confirmTitle || "Confirmer l'action";
  const msg   = btn.dataset.confirmMsg || "Voulez-vous continuer ?";
  const modalId = btn.dataset.confirmModal || "confirmModal";

  qs(`#${modalId} .modal-title`).textContent = title;
  qs(`#${modalId} [data-modal-msg]`).textContent = msg;

  const actionHint = btn.dataset.confirmActionHint || "Action à brancher côté PHP.";
  qs(`#${modalId} [data-modal-hint]`).textContent = actionHint;

  openModal(modalId);
});

document.addEventListener("click", (e) => {
  const x = e.target.closest("[data-modal-close]");
  if(!x) return;
  const bd = e.target.closest(".modal-backdrop");
  if(bd) closeModal(bd.id);
});


// Register page: show/hide coach fields safely
const roleSelect = document.getElementById("role");
const coachFields = document.getElementById("coachFields");

if (roleSelect && coachFields) {
  const exp = document.getElementById("experience");
  const disc = document.getElementById("discipline");
  const desc = document.getElementById("description");

  function setRequired(el, val) {
    if (el) el.required = val;
  }

  function toggleCoachFields() {
    const isCoach = roleSelect.value === "coach";
    coachFields.style.display = isCoach ? "block" : "none";

    setRequired(exp, isCoach);
    setRequired(disc, isCoach);
    setRequired(desc, isCoach);
  }

  toggleCoachFields();
  roleSelect.addEventListener("change", toggleCoachFields);
}