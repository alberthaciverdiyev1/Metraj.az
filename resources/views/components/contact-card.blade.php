@props([
'title' => 'Contact Sellers',
])
<div class="contact-card">
    <h3>Contact Sellers</h3>
    <div class="flex avatar-info">
        <img src="https://themesflat.co/html/proty/images/avatar/seller.jpg" alt="Shara Conner" class="avatar">
        <div class="avatar-info-text">
            <h4>Shara Conner</h4>
            <p><a href="tel:1-333-345-6868" class="phone"><i class="bi bi-telephone"></i> 1-333-345-6868</a></p>
            <p><a href="mailto:themesflat@gmail.com" class="email">
                    <i class="bi bi-envelope"></i> themesflat@gmail.com</a></p>
        </div>

    </div>



    <form id="contact-form">
        <input type="text" id="name" name="name" placeholder="Full Name" required>
        <textarea id="message" name="message" placeholder="How can an agent help" required></textarea>
        <button
            class="send-message-btn all-btn button-hover"
            type="submit">Send message</button>
    </form>
</div>