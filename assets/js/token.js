(function ($) {
    $(document).ready(function () {
        $('#login-form').submit(function (event) {
            let form = this;
            $('.Login__submitBtn').attr('disabled', true).css('cursor', 'wait');
            event.preventDefault();
            let userName = $('#user_login').val();
            let pass = $('#user_pass').val();

            let url = `${window.location.origin}/wp-json/simple-jwt-authentication/v1/token`;
            if (window.location.hostname === 'localhost') {
                url = 'http://localhost/flerson/wp-json/simple-jwt-authentication/v1/token';
            }
            fetch(`${url}?username=${userName}&password=${pass}`, {
                method: 'POST'
            })
                .then(res => {
                    if (res.ok) {
                        return res.json();
                    } else {
                        throw (res.statusText);
                    }
                })
                .then(data => {
                    if (data.role === 'employer') {
                        const expirationDate = new Date(data.token_expires * 1000);
                        localStorage.setItem('token', data.token);
                        localStorage.setItem('userId', data.user_id);
                        localStorage.setItem('expirationDate', expirationDate);
                        localStorage.setItem('userName', data.username);
                        localStorage.setItem('userRole', data.role);
                    }
                    form.submit();
                })
                .catch(err => {
                    console.log(err);
                    $('.Login__submitBtn').attr('disabled', false).css('cursor', 'auto');
                })
        })
    });
})(jQuery);