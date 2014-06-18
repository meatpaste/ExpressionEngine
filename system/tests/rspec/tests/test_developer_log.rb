require './bootstrap.rb'

feature 'Developer Log' do

  before(:each) do
    cp_session

    @page = DeveloperLog.new
    @page.load

    # These should always be true at all times if not something has gone wrong
    @page.displayed?
    @page.title.text.should eq 'Developer Logs'
    @page.should have_phrase_search
    @page.should have_submit_button
    @page.should have_date_filter
    @page.should have_perpage_filter
  end

  it 'shows the Developer Logs page' do
    @page.should have_remove_all
    @page.should have_pagination

    @page.perpage_filter.value.should eq "50"

    @page.should have(6).pages
    @page.pages.map {|name| name.text}.should == ["First", "1", "2", "3", "Next", "Last"]

    @page.should have(50).items # Default is 50 per page
  end

  # Confirming phrase search
  # @TODO pending phrase search working

  it 'filters by date' do
    @page.date_filter.select "Last 24 Hours"
    @page.submit_button.click

    @page.date_filter.has_select?('filter_by_date', :selected => "Last 24 Hours")
    # @TODO when we can add these via a fixture we can test the relative date filter
    # @page.should have(1).items
  end

  it 'can change page size' do
    @page.perpage_filter.select "25 results"
    @page.submit_button.click

    @page.perpage_filter.has_select?('perpage', :selected => "25 results")
    @page.should have(25).items
    @page.should have_pagination
    @page.should have(6).pages
    @page.pages.map {|name| name.text}.should == ["First", "1", "2", "3", "Next", "Last"]
  end

  # @TODO when we can add these via a fixture we can test the relative date filter
  # Confirming combining filters work
  # it 'can combine date and page size filters' do
  #   @page.perpage_filter.select "150 results"
  #   @page.submit_button.click
  #
  #   @page.perpage_filter.has_select?('perpage', :selected => "150 results")
  #   @page.should have(150).items
  #   @page.should have_pagination
  #   @page.should have_text "johndoe"
  #
  #   @page.perpage_filter.select "150 results"
  #   @page.username_filter.select "admin"
  #   @page.submit_button.click
  #
  #   @page.perpage_filter.has_select?('perpage', :selected => "150 results")
  #   @page.username_filter.has_select?('filter_by_username', :selected => "admin")
  #   @page.should have(150).items
  #   @page.should have_pagination
  #   @page.should_not have_text "johndoe"
  # end

  # @TODO pending phrase search working
  # it 'can combine phrase search with filters' do
  # end

  # Confirming the log deletion action
  it 'can remove a single entry' do
    # the first item in the list
    @page.items[0].find('li.remove a').click

    @page.should have_alert
    @page.should_not have_text "6/18/14 5:08 AM"
  end

  it 'can remove all entries' do
    @page.remove_all.click

    @page.should have_alert
    @page.should have_no_results
    @page.should_not have_pagination
  end

  # Confirming Pagination behavior
  it 'shows the Prev button when on page 2' do
    click_link "Next"

    @page.should have_pagination
    @page.should have(7).pages
    @page.pages.map {|name| name.text}.should == ["First", "Previous", "1", "2", "3", "Next", "Last"]
  end

  it 'does not show Next on the last page' do
    click_link "Last"

    @page.should have_pagination
    @page.should have(6).pages
    @page.pages.map {|name| name.text}.should == ["First", "Previous", "3", "4", "5", "Last"]
  end

  it 'does not lose a filter value when paginating' do
    @page.perpage_filter.select "25 results"
    @page.submit_button.click

    @page.perpage_filter.has_select?('perpage', :selected => "25 results")
    @page.should have(25).items

    click_link "Next"

    @page.perpage_filter.has_select?('perpage', :selected => "25 results")
    @page.should have(25).items
    @page.should have_pagination
    @page.should have(7).pages
    @page.pages.map {|name| name.text}.should == ["First", "Previous", "1", "2", "3", "Next", "Last"]
  end

end