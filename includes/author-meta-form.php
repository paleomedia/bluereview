<?php

// Form for extra profile fields
// It's a little big so I thought it would be good to move it.

function get_more_about_authors($user) {
    $userData = blue_author_info($user->ID);
    ?>

    <h3>Extra profile information</h3>

    <table class="form-table">

        <tr>
            <th><label for="title">Title</label></th>

            <td>
                <input type="text" name="title" id="title" value="<?php echo esc_attr($userData['title']); ?>" class="regular-text" /><br />
                <span class="description">What should we call you?</span>
            </td>
        </tr>

        <tr>
            <th><label for="avatar">Backup avatar URL</label></th>

            <td>
                <input type="text" name="avatar" id="avatar" value="<?php echo esc_attr($userData['avatar']); ?>" class="regular-text" /><br />
                <span class="description">What should we use if there's no gravatar?</span>
            </td>
        </tr>

        <tr>
            <th><label for="shortBio">Short bio</label></th>

            <td colspan="2">
                <textarea name="shortBio" id="shortBio" class="regular-text" ><?php echo esc_attr($userData['shortBio']); ?></textarea><br />
                <span class="description">For those cases where the full bio doesn't make sense</span>
            </td>
        </tr>
        <tr>
            <th><label for="twitter">Twitter</label></th>

            <td>
                <input type="text" name="twitter" id="twitter" value="<?php echo esc_attr($userData['twitter']); ?>" class="regular-text" /><br />
                <span class="description">Twitter handle.</span>
            </td>
        </tr>
        <tr>
            <th><label for="facebook">Facebook</label></th>

            <td>
                <input type="text" name="facebook" id="facebook" value="<?php echo esc_attr($userData['facebook']); ?>" class="regular-text" /><br />
                <span class="description">facebook profile URL.</span>
            </td>
        </tr>
        <tr>
            <th><label for="gplus">Google+</label></th>
            <td>
                <input type="text" name="gplus" id="gplus" value="<?php echo esc_attr($userData['gplus']); ?>" class="regular-text" /><br />
                <span class="description">Google+ profile URL.</span>
            </td>
        </tr>
        <tr>
            <th><label for="website">Website</label></th>
            <td>
                <input type="text" name="website" id="website" value="<?php echo esc_attr($userData['website']); ?>" class="regular-text" /><br />
                <span class="description">Author's website</span>
            </td>
        </tr>
        <tr>
            <th><label for="books">Publications you've authored?</label></th>
                        <td>
                <input type="text" name="booktitle1" id="booktitle1" value="<?php echo esc_attr($userData['books']['book1']['title']); ?>" class="regular-text" /><br />
                <span class="description">Title</span>
            </td>

            <td>
                <input type="text" name="book1" id="book1" value="<?php echo esc_attr($userData['books']['book1']['cover']); ?>" class="regular-text" /><br />
                <span class="description">Cover url</span>
            </td>

            <td>
                <input type="text" name="bookurl1" id="bookurl1" value="<?php echo esc_attr($userData['books']['book1']['url']); ?>" class="regular-text" /><br />
                <span class="description">Publication URL</span>
            </td>
        </tr>
        <tr>
            <th><label for="books">More publications?</label></th>
                        <td>
                <input type="text" name="booktitle2" id="booktitle2" value="<?php echo esc_attr($userData['books']['book2']['title']); ?>" class="regular-text" /><br />
                <span class="description">Second title</span>
            </td>
            <td>
                <input type="text" name="book2" id="book" value="<?php echo esc_attr($userData['books']['book2']['cover']); ?>" class="regular-text" /><br />
                <span class="description">Second cover url</span>
            </td>

            <td>
                <input type="text" name="bookurl2" id="bookurl2" value="<?php echo esc_attr($userData['books']['book2']['url']); ?>" class="regular-text" /><br />
                <span class="description">Second URL</span>
            </td>
        </tr>
        <tr>
            <th><label for="book3">Even more publications? Showoff.</label></th>
                        <td>
                <input type="text" name="booktitle3" id="booktitle3" value="<?php echo esc_attr($userData['books']['book3']['title']); ?>" class="regular-text" /><br />
                <span class="description">Third title</span>
            </td>
            <td>
                <input type="text" name="book3" id="book3" value="<?php echo esc_attr($userData['books']['book3']['cover']); ?>" class="regular-text" /><br />
                <span class="description">Third cover url</span>
            </td>
            <td>
                <input type="text" name="bookurl3" id="bookurl3" value="<?php echo esc_attr($userData['books']['book3']['url']); ?>" class="regular-text" /><br />
                <span class="description">Third URL</span>
            </td>
        </tr>

    </table>
<?php }
?>
