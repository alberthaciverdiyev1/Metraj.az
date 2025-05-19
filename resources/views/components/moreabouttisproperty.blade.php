<div class="contact-form">
    <form action="">
        <h3>More about this Property</h3>
        <input type="text" placeholder="Your name" />
        <input type="email" placeholder="Email" />
        <input type="tel" placeholder="Phone" />
        <textarea placeholder="Message"></textarea>
        <div class="buttons">
            <button class="send-btn">
                <i class="fa fa-envelope"></i> 
                Email Agent
            </button>
           
        </div>
    </form>
</div>

<style>
    #contact{
  width: 35%;
}
.contact-form {
  width: 100%;
  padding: 30px;
  border: 1px solid var(--border-color);
  border-radius: 15px;
  background-color: var(--white);
}
.contact-form .form{
  width: 100;
}
.contact-form h3 {
  margin-bottom: 20px;
  color: var(--text-color);
  font-size: 22px;
}

.contact-form input,
.contact-form textarea {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 15px;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  font-size: 15px;
  color: var(--text-color);
  resize: none;
}

.contact-form textarea {
  height: 100px;
}

.buttons {
  display: flex;
  gap: 10px;
}

.send-btn{
  flex: 1;
  padding: 12px;
  font-size: 15px;
  border-radius: 12px;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-weight: 500;
}

.send-btn {
  background-color: var(--primary);
  color: white;
  border: none;
}


.send-btn {
  position: relative;
  overflow: hidden;
  z-index: 1;
  transition: color 0.3s ease;
}

.send-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 100%;
  z-index: -1;
  transition: 0.4s ease;
}

.send-btn:hover::before {
  width: 100%;
  background-color:var(--text-color);
}

.send-btn:hover {
  color: var(--white);
}


</style>