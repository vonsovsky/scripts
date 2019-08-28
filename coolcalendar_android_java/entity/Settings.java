package fi.jamk.coolcalendar.entity;

public class Settings {
	private User user;
	private Boolean countdown_on;
	private Boolean countdown_timer;
	private String color;

	public User getUser() {
		return user;
	}

	public void setUser(User user) {
		this.user = user;
	}

	public Boolean getCountdown_on() {
		return countdown_on;
	}

	public void setCountdown_on(Boolean countdown_on) {
		this.countdown_on = countdown_on;
	}

	public Boolean getCountdown_timer() {
		return countdown_timer;
	}

	public void setCountdown_timer(Boolean countdown_timer) {
		this.countdown_timer = countdown_timer;
	}

	public String getColor() {
		return color;
	}

	public void setColor(String color) {
		this.color = color;
	}

}
