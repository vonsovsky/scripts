package fi.jamk.coolcalendar.entity;

import java.sql.Date;

public class Event {
	private User user;
	private Course course;
	private Date deadline;
	private Boolean countdown_on;

	public User getUser() {
		return user;
	}

	public void setUser(User user) {
		this.user = user;
	}

	public Course getCourse() {
		return course;
	}

	public void setCourse(Course course) {
		this.course = course;
	}

	public Date getDeadline() {
		return deadline;
	}

	public void setDeadline(Date deadline) {
		this.deadline = deadline;
	}

	public Boolean getCountdown_on() {
		return countdown_on;
	}

	public void setCountdown_on(Boolean countdown_on) {
		this.countdown_on = countdown_on;
	}
}
